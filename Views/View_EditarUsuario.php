<?php  
require_once "Conexao.php";
session_start();
	if((!isset ($_SESSION['login']) == true))
	{
	  unset($_SESSION['login']);
	  header('location:index.php');
	 }

	$logado = $_SESSION['login'];
	$codigo = $_SESSION['codigo'];
	$tipo = $_SESSION['tipo'];

	$sql = mysql_query("SELECT V_NOME, V_EMAIL, V_IDADE, V_CPF, V_LOGIN, V_SENHA, V_TELEFONE, V_CELULAR, V_CEP, V_CIDADE, V_BAIRRO, V_UF FROM usuario WHERE N_COD_USUARIO = '$codigo' ");
	$linha = mysql_fetch_assoc($sql);
	if (!$linha) {
	  //Se o select não retornou registros, é porque não tem o que apagar
	  header("Location: painel.php");
	  die();
	}
	$nome = $linha["V_NOME"];
	$email = $linha["V_EMAIL"];
	$idade = $linha["V_IDADE"];
	$cpf = $linha["V_CPF"];
	$login = $linha["V_LOGIN"];
	$senha = $linha["V_SENHA"];
	$telefone = $linha["V_TELEFONE"];
	$celular = $linha["V_CELULAR"];
	$cep = $linha["V_CEP"];
	$cidade = $linha["V_CIDADE"];
	$bairro = $linha["V_BAIRRO"];
	$uf = $linha["V_UF"];
	?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<link rel="stylesheet" type="text/css" href="CSS/Menu.css">
    <link rel="stylesheet" type="text/css" href="CSS/Rodape.css">
	<title>Troca Livro</title>
	<script>
function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
  }
  
}


</script>
</head>
<body>
	   <?php include('View_topo.php'); ?>

<div id='corpo' style="height: 680px;">
<h2>Editar Perfil </h2>
	<form name="CadastroUsuario" method="post" action="RepositorioEditarUsuario.php">
		<table id="cad_table">
			<tr>
				<td>Nome:*</td>
				<td><input type="text" name="nome" id="nome" class="txt" value="<?php echo $nome; ?>" size=35 required/></td>
			</tr>
			<tr>
				<td>Email:*</td>
				<td><input type="email" name="email" id="email" class="txt" value="<?php echo $email; ?>" size=35 required/></td>
			</tr>
			<tr>
				<td>Idade: </td>
				<td><input type="text" name="idade" id="idade" class="txt" value="<?php echo $idade; ?>" size=2 required/>&nbsp Anos</td>
			</tr>
			<tr>
				<td>CPF:*</td>
				<td><input type="text" name="cpf" id="cpf" class="txt2" maxlength="14" OnKeyPress="formatar('###.###.###-##', this)" value="<?php echo $cpf; ?>" size=35 required='aa'/></td>
			</tr>
			<tr>
				<td>Login:*</td>
				<td><input type="text" name="login" id="login" class="txt1" maxlength="10" value="<?php echo $login; ?>" size=35 required/></td>
			</tr>
			<tr>
				<td>Senha:*</td>
				<td><input type="password" name="senha" id="senha" class="txt1" maxlength="15" value="<?php echo $senha; ?>" size=35 required/></td>
			</tr>
            <tr>
				<td>Telefone:</td>
				<td><input type="tel" name="telefone" id="telefone" class="txt2" maxlength="12" OnKeyPress="formatar('##-####-####', this)" value="<?php echo $telefone; ?>" size=35/></td>
			</tr>
			<tr>
				<td>Celular:</td>
				<td><input type="tel" name="celular" id="celular" class="txt2" maxlength="12" OnKeyPress="formatar('##-####-####', this)" value="<?php echo $celular; ?>" size=35/></td>
			</tr>
			<tr>
				<td>Cep:*</td>
				<td><input type="text" name="cep" id="cep" class="txt2" maxlength="10" OnKeyPress="formatar('#####-###', this)" value="<?php echo $cep; ?>" size=35 required/></td>
			</tr>
			<tr>
				<td>cidade:</td>
				<td><input type="text" name="cidade" id="cidade" class="txt2" maxlength="100"  value="<?php echo $cidade; ?>" size=35/></td>
			</tr>
			<tr>
				<td>Bairro:</td>
				<td><input type="text" name="bairro" id="bairro" class="txt" maxlength="50" value="<?php echo $bairro; ?>" size=35 /></td>
			</tr>

			<tr>
				<td>UF:</td>
				<td><input type="text" name="uf" id="uf" class="txt3" maxlength="2" value="<?php echo $uf; ?>" size=2/></td>
			</tr>

				<td colspan="2"><input class='btn' type="submit" value="Salvar" id="buton1" name="btvalidar">
					<br><input class='btn' type="button" value="Cancelar" onclick="location.href='PerfilUsuario.php'" id="buton1" name="btvalidar">
				
				</td>
			</tr>
		</table>
		
		
	</form>
	<p class='campo-obrigatorio'>(*) Campos Obrigatórios</p>
</div>
    <?php include('View_rodape.php'); ?>
</body>
</html>


