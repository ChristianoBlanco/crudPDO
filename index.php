<?php

include_once("connPDO.php");
print $id    = @$_REQUEST["id"];		
print $curso = @$_REQUEST["curso"];
print $nome  = @$_REQUEST["nome"];
//Incluímos um código aqui...
/*$id           = -1;
$curso        = "";
$nome         = "";*/

//Validando a existência dos dados
if(isset($_POST["curso"]) && isset($_POST["nome"]))
{
    if(empty($_POST["curso"]))
		$erro = "Campo curso obrigatório";
	else
	if(empty($_POST["nome"]))
		$erro = "Campo nome obrigatório";
	else
	{
		 //Alteramos aqui também.
		 //Agora, o $id, pode vir com o valor -1, que nos indica novo registro, 
		 //ou, vir com um valor diferente de -1, ou seja, 
                 //o código do registro no banco, que nos indica alteração dos dados.
		 
		
	  	//Se o id for -1, vamos realizar o cadastro ou alteração dos dados enviados.
		if($id == "")
		{
		  $stmt = $obj_mysqli->prepare("INSERT INTO curso(curso,nome) VALUES (?,?)");
		  $stmt->bind_param('ss',$curso,$nome);
		  
		  if(!$stmt->execute())
		  {
	  		 $erro = $stmt->error;
		  }
		  else
		  {
		   	 $sucesso = "Dados cadastrados com sucesso!";
	 		 header("Location:index.php");
		     exit;
		  }
		}else{
		 
			$stmt = $obj_mysqli->prepare("UPDATE curso SET curso=?, nome=? WHERE id_curso = ? ");
			$stmt->bind_param('iss', $id_curso,$curso, $nome);
		
			if(!$stmt->execute())
			{
				$erro = $stmt->error;
			}
			else
			{
				header("Location:index.php");
				exit;
			}
		  
		//retorna um erro.
		
		}
	}	
}else{
//Incluimos este bloco, onde vamos verificar a existência do id passado...
  if(isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"]))
  {
   //...pegamos aqui o id passado...
   $id = (int)$_REQUEST["id"];	
   //...montamos a consulta que será realizada....
   if(isset($_GET["del"]))
	{
		$stmt = $obj_mysqli->prepare("DELETE FROM curso WHERE id_curso = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		
		header("Location:index.php");
		exit;
	}
	else
	{
	$stmt = $obj_mysqli->prepare("SELECT * FROM curso WHERE id_curso = ?"); //
        //passamos o id como parâmetro, do tipo i = int, inteiro...
	$stmt->bind_param('i', $id_curso);
        //...mandamos executar a consulta...
	$stmt->execute();
	//...retornamos o resultado, e atribuímos à variável $result...
	$result = $stmt->get_result();
        //...atribuímos o retorno, como um array de valores,
        //por meio do método fetch_assoc, que realiza um associação dos valores em forma de array...
        $aux_query = $result->fetch_assoc();
	//...onde aqui, nós atribuímos às variáveis.
	$curso = $aux_query["curso"];
	$nome  = $aux_query["nome"];

	$stmt->close();
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
<?php
if(isset($erro))
	echo '<div style="color:#F00">'.$erro.'</div><br/><br/>';
else
if(isset($sucesso))
	echo '<div style="color:#00f">'.$sucesso.'</div><br/><br/>';
$recebe_id = @$_REQUEST[id];
if(!isset($recebe_id)){
 $recebe_id = "-1";
}
$result_1 = $obj_mysqli->query("SELECT * FROM curso WHERE id_curso='$recebe_id'");	
$row_query = $result_1->fetch_assoc()
?>
<form name="form1" action="<?=$_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
	  Curso:<br/> 
	  <input type="text" name="curso" value="<?= $row_query['curso']; ?>" placeholder="Qual seu curso?"><br/><br/>
	  Nome:<br/> 
	  <input type="text" name="nome" value="<?= $row_query['nome']; ?>" placeholder="Qual seu nome?"><br/><br/>
	  <br/><br/>
	  <input type="hidden" value="<?= $row_query['id_curso']; ?>" name="id" >
	  <button type="submit">Cadastrar</button>
</form>
<br>
	<br>
	<table width="400px" border="0" cellspacing="0">
	  <tr>
	    <td><strong>#</strong></td>
	    <td><strong>Curso</strong></td>
	    <td><strong>Nome</strong></td>
	    <td><strong>#</strong></td>
        <td><strong>#</strong></td>
	  </tr>
	<?php
	$result = $obj_mysqli->query("SELECT * FROM curso");
	while ($aux_query = $result->fetch_assoc()) 
    {
	  echo '<tr>';
	  echo '  <td>'.$aux_query["id_curso"].'</td>';
	  echo '  <td>'.$aux_query["curso"].'</td>';
	  echo '  <td>'.$aux_query["nome"].'</td>';
	  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["id_curso"].'">Editar</a></td>';
	  echo '  <td><a href="'.$_SERVER["PHP_SELF"].'?id='.$aux_query["id_curso"].'&del=true">Excluir</a></td>';
	  echo '</tr>';
	}
	?>
	</table>
    <br />
    <a href="<?=$_SERVER["PHP_SELF"]?>" target="_parent">Novo Cadastro</a>
</body>
</html>