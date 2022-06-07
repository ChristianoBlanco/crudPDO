<?
include_once("connPDO.php");
//Validando a existência dos dados
if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["cidade"]) && isset($_POST["uf"]))
{
    if(empty($_POST["nome"]))
		$erro = "Campo nome obrigatório";
	else
	if(empty($_POST["email"]))
		$erro = "Campo e-mail obrigatório";
	else
	{
		//Vamos realizar o cadastro ou alteração dos dados enviados.
		$curso   = $_POST["curso"];
		$nome  = $_POST["nome"];
		
		$stmt = $obj_mysqli->prepare("INSERT INTO 'curso' ('curso','nome') VALUES (?,?)");
		$stmt->bind_param('ss', $curso, $nome);
		
		if(!$stmt->execute())
		{
			$erro = $stmt->error;
		}
		else
		{
			$sucesso = "Dados cadastrados com sucesso!";
		}
	}	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?
if(isset($erro))
	echo '<div style="color:#F00">'.$erro.'</div><br/><br/>';
else
if(isset($sucesso))
	echo '<div style="color:#00f">'.$sucesso.'</div><br/><br/>';

?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
	  Curso:<br/> 
	  <input type="text" name="curso" placeholder="Qual seu curso?"><br/><br/>
	  Nome:<br/> 
	  <input type="email" name="nome" placeholder="Qual seu nome?"><br/><br/>
	  <br/><br/>
	  <input type="hidden" value="-1" name="id" >
	  <button type="submit">Cadastrar</button>
</form>
<br>
	<br>
	<table width="400px" border="0" cellspacing="0">
	  <tr>
	    <td><strong>#</strong></td>
	    <td><strong>Nome</strong></td>
	    <td><strong>Email</strong></td>
	    <td><strong>Cidade</strong></td>
	    <td><strong>UF</strong></td>
	    <td><strong>#</strong></td>
	  </tr>
	<?
	$result = $obj_mysqli->query("SELECT * FROM 'curso'");
	while ($aux_query = $result->fetch_assoc()) 
    {
	  echo '<tr>';
	  echo '  <td>'.$aux_query["id_curso"].'</td>';
	  echo '  <td>'.$aux_query["curso"].'</td>';
	  echo '  <td>'.$aux_query["nome"].'</td>';
	  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["id_curso"].'">Editar</a></td>';
	  echo '</tr>';
	}
	?>
	</table>
</body>
</html>