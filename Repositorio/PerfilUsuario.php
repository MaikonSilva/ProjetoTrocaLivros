<!DOCTYPE html>
<?php
require_once "../Dados/Conexao.php";
session_start();
if((!isset ($_SESSION['login']) == true))
{
  unset($_SESSION['login']);
  header('location:index.php');
}

$logado = $_SESSION['login'];
$codigo = $_SESSION['codigo'];
$tipo = $_SESSION['tipo'];
?>
<html>
<head>
    <title>Troca Livro</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../CSS/PerfilUsuario.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Menu.css">
    <link rel="stylesheet" type="text/css" href="../CSS/Rodape.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"/></script>
    <script>
    $(document).ready(function(){
    $(".text_container").click(function(){
        $(".listar-livro").hide(1000);
    });
    $(".text_container2").click(function(){
        $(".listar-livro").show(1000);
    });
    });
    </script>
</head>
<body>
<?php include('../Views/View_topo.php'); ?>

<?php
$query1 = mysql_query("SELECT V_NOME, V_CIDADE,V_SEXO, V_UF, V_EMAIL, V_CEP, V_BAIRRO, D_DATA_CADASTRO, V_IDADE, D_DATA_ULTIMO_LOGIN, V_FOTO FROM usuario WHERE V_LOGIN = '$logado'");
$query2 = mysql_query("SELECT COUNT(*) FROM livro WHERE N_COD_USUARIO_IE = '$codigo'");
$query3 = mysql_query("SELECT COUNT(*) FROM livro_desejado WHERE N_COD_USUARIO_IE = '$codigo'");

$query4 = mysql_query("SELECT COUNT(*), livro.N_COD_USUARIO_IE FROM troca INNER JOIN livro on livro.N_COD_LIVRO = troca.N_COD_LIVRO_SOLICITANTE  WHERE livro.N_COD_USUARIO_IE = '$codigo' and troca.V_STATUS = 'Pendente'");

$query5 = mysql_query("SELECT COUNT(*) FROM TROCA INNER JOIN LIVRO AS LIVRO_SOLICITADO ON LIVRO_SOLICITADO.N_COD_LIVRO = TROCA.N_COD_LIVRO INNER JOIN LIVRO AS LIVRO_SOLICITANTE ON LIVRO_SOLICITANTE.N_COD_LIVRO = TROCA.N_COD_LIVRO_SOLICITANTE WHERE LIVRO_SOLICITADO.N_COD_USUARIO_IE = '.$codigo.' or LIVRO_SOLICITANTE.N_COD_USUARIO_IE = '.$codigo.'");
$query7 = mysql_query("SELECT COUNT(*), livro.N_COD_USUARIO_IE FROM troca INNER JOIN livro on livro.N_COD_LIVRO = troca.N_COD_LIVRO  WHERE livro.N_COD_USUARIO_IE = '$codigo' AND B_ATIVO = 'F'");

$query8 = mysql_query("SELECT N_COD_LIVRO, V_TITULO, V_AUTOR, V_ANO, V_FOTO, V_OBSERVACAO, V_ESTADO_LIVRO, categoria_livro.V_GENERO, V_EDITORA FROM livro INNER JOIN categoria_livro on categoria_livro.N_COD_CATEGORIA = livro.N_COD_CATEGORIA_IE WHERE N_COD_USUARIO_IE = '$codigo'");

$query9 = mysql_query("SELECT N_COD_LIVRO_DESEJADO, V_TITULO, D_ANO, N_COD_USUARIO_IE, N_COD_CATEGORIA_IE, categoria_livro.V_GENERO FROM livro_desejado INNER JOIN categoria_livro on categoria_livro.N_COD_CATEGORIA = livro_desejado.N_COD_CATEGORIA_IE where N_COD_USUARIO_IE = '$codigo'");

$query10 =  mysql_query("SELECT TROCA.*, (LIVRO_SOLICITADO.V_FOTO) AS FOTO_SOLICITADO, (LIVRO_SOLICITANTE.V_FOTO) AS FOTO_SOLICITANTE, (USUARIO_SOLICITADO.V_NOME) AS NOME_SOLICITANE, (USUARIO_SOLICITANTE.V_NOME) AS NOME_SOLICITADO   FROM TROCA 
INNER JOIN LIVRO AS LIVRO_SOLICITADO ON LIVRO_SOLICITADO.N_COD_LIVRO = TROCA.N_COD_LIVRO INNER JOIN LIVRO AS LIVRO_SOLICITANTE ON LIVRO_SOLICITANTE.N_COD_LIVRO = TROCA.N_COD_LIVRO_SOLICITANTE INNER JOIN USUARIO AS USUARIO_SOLICITADO ON USUARIO_SOLICITADO.N_COD_USUARIO = LIVRO_SOLICITADO.N_COD_USUARIO_IE
INNER JOIN USUARIO AS USUARIO_SOLICITANTE ON USUARIO_SOLICITANTE.N_COD_USUARIO = LIVRO_SOLICITANTE.N_COD_USUARIO_IE WHERE LIVRO_SOLICITADO.N_COD_USUARIO_IE = '$codigo' or LIVRO_SOLICITANTE.N_COD_USUARIO_IE = '$codigo'");

$query11 = mysql_query("SELECT COUNT(*) FROM AJUDA WHERE N_COD_USUARIO_IE = '$codigo'" );


$mensagens = mysql_num_rows($query11);


$dados = mysql_fetch_assoc($query1);

$Livros = mysql_fetch_row($query2);
$LivrosDesejados = mysql_fetch_row($query3);
$Solitacoes = mysql_fetch_row($query4);
$TrocasPendentes = mysql_fetch_row($query5);
$TrocasRealizadas = mysql_fetch_row($query7);

$nome = $dados['V_NOME'];
$sexo = $dados['V_SEXO'];
$cidade = $dados['V_CIDADE'];
$uf = $dados['V_UF'];
$email = $dados['V_EMAIL'];
$cep = $dados['V_CEP'];
$bairro = $dados['V_BAIRRO'];
$datacadastro = $dados['D_DATA_CADASTRO'];
$idade = $dados['V_IDADE'];
$datalogin = $dados['D_DATA_ULTIMO_LOGIN'];
if ($dados['V_FOTO']) {
  $foto = $dados['V_FOTO'];
}else{
  if ($sexo == 'F') {
    $foto = "FotoPerfilUsuario/foto_padraoF.jpg";
  } else {
    $foto = "FotoPerfilUsuario/foto_padraoM.jpg";
  }
}

$QuantidadeLivros = $Livros[0];
$QuantidadeLivrosDesejados = $LivrosDesejados[0];
$status = $Solitacoes[1];
$QuantidadeSolicitacoes = $Solitacoes[0];
$QuantidadeTrocasPendentes = $TrocasPendentes[0];
$QuantidadeTrocasRealizadas = $TrocasRealizadas[0];
?>

  <div id='corpo'>
    <h2>Perfil</h2>
      <div id='lateral'>
         <p ><img class="foto_usuario" src="<?php echo $foto; ?>"width="198" height="198"></p>
         <form action="?go=salvarfoto" method="post" enctype="multipart/form-data" name="cadastro" >
           Foto de exibição:<br />
           <input style="width: 200px;" type="file" name="foto" /><br />
           <input  type="submit" value="Mudar Foto" class="btnPerfil" id="btnPerfil">
         </form>
         <input  type="submit" value="Editar Perfil" onclick="location.href='EditarUsuario.php'" class="btnPerfil" id="btnPerfil"> 
         <div id='quantidaderegistro'>
          <p class='info-lateral'>Livros Publicados: <?php echo $QuantidadeLivros; ?> </p>
          <p class='info-lateral'>Livros Desejados: <?php echo $QuantidadeLivrosDesejados; ?></p>
          <p class='info-lateral'>Solicitações : <a href="Solicitacao.php"><?php echo $QuantidadeSolicitacoes; ?></a></p>
          <p class='info-lateral'>Trocas Pendentes : <?php echo $QuantidadeTrocasPendentes; ?></p>
          <p class='info-lateral'>Trocas Realizadas : <?php echo $QuantidadeTrocasRealizadas; ?></p>
      </div>
  </div>

  <div id='centro'>

    <form class='form_mensagens' action="Mensagens.php">
    <input type='hidden' name="codigousuario" id="codigousuario" value="<?php echo $codigo;?>" >
    <input class="btnMensagens" type='submit' onclick value='Mensagens: <?php echo $mensagens;?>'></input>
    </form>

    <p class="nome_usuario"><?php echo $nome; ?></p>
    <p class="cidade_uf_usuario"><?php echo $cidade; ?>,<?php echo $uf; ?></p>
  

    <fieldset class="fieldset-info-central"><legend>Informações Pessoais</legend>
      <p class='info-central'>País: Brasil</p>
      <p class='info-central'>Estado: <?php echo $uf; ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp CEP: <?php echo $cep; ?></p>
      <p class='info-central'>Cidade / Município: <?php echo $cidade; ?></p>
      <p class='info-central'>Bairro: <?php echo $bairro; ?></p>
      <p class='info-central'>Endereço de Email: <?php echo $email; ?></p>
      <p class='info-central'>Idade: <?php echo $idade; ?> Anos</p>
      <p class='info-central'>Data de Cadastro: <?php echo date('d/m/Y', strtotime($datacadastro)); ?></p>
      <p class='info-central'>Data Último Login: <?php echo date('d/m/Y \á\s H:i:s', strtotime($datalogin)); ?></p>

    </fieldset>

    <h4 class="centro-esquerda"><a href="CadastroLivro.php">Cadastrar Livro</a><h4>
    <h2 class="MeusLivros">Meus Livros</h2>


    <table class="listar-livro-exibicao">
      <td style="width: 60px; margin-left: -10px;">Foto</td>
      <td style="width: 40px;">Título</td>
      <td style="width: 40px;">Autor</td>
      <td style="width: 40px;">Editora</td>
      <td style="width: 40px;">Gênero</td>
      <td style="width: 35px;">Estado</td>
    </table>

    <?php
      while ($linha=mysql_fetch_array($query8)){
        $codigolivro = $linha["N_COD_LIVRO"];
        $titulo= $linha['V_TITULO']; 
        $autor= $linha['V_AUTOR']; 
        $ano= $linha['V_ANO']; 
        $foto= $linha['V_FOTO'];
        $observacao= $linha['V_OBSERVACAO']; 
        $editora = $linha['V_EDITORA'];
        $estado_livro= $linha['V_ESTADO_LIVRO'];   
        $genero= $linha['V_GENERO']; 
    ?>

    <form id="form2" name="form2" method="post" action="VisualizarLivro.php">
      <h5 class="listar-livro">
      <table class="table-listar-livro">
        <tr class="listar-livro-tr">
          <td class="listar-livro-foto"><img src="<?php echo $foto; ?>"width="100" height="100"></td>
          <td class="listar-livro-titulo"><?php echo $titulo; ?></td> <br>
          <td class="listar-livro-autor"><?php echo $autor;?></td>
          <td class="listar-livro-genero"><?php echo $editora;?></td>
          <td class="listar-livro-genero"><?php echo $genero;?></td>
           <td class="listar-livro-estado-livro"><?php echo $estado_livro;?></td>
          <input type='hidden' name="codigolivro" id="codigolivro" value="<?php echo $codigolivro;?>" >
          <td class="listar-livro-genero"><input type="submit" name="Ver"  id="Ver"   value="Ver" /></td>
      </tr>
      </table>
      </h5>
    </form>

    
    <?php } ?>

    <h4 class="centro-esquerda-desejados"><a href="CadastroLivroDesejado.php">Cadastrar Livro</a><h4>
    <h2 class="MeusLivros-desejados">Meus Livros Desejados</h2>


    <table class="listar-livro-exibidos-desejados">
      <td>Título</td>
      <td>Ano</td>
      <td class="listar-livro-exibidos-desejados-ultimo">Gênero</td>
    </table>

    <table class="listar-livro-exibidos-desejados-preenche">
      <td> </td>
    </table>
    


    <?php
      while ($linhadesejado=mysql_fetch_array($query9)){
        $codigolivrodesejado = $linhadesejado["N_COD_LIVRO_DESEJADO"];
        $titulodesejado= $linhadesejado['V_TITULO']; 
        $anodesejado= $linhadesejado['D_ANO']; 
        $generodesejado= $linhadesejado['V_GENERO']; 
    ?>



    <form id="form2" name="form2" method="post" action="">
      <h5 class="listar-livro-desejados">
      <table class="table-listar-livro-desejados">
        <tr class="listar-livro-tr-desejados">
          <td class="listar-livro-titulo-desejados"><?php echo $titulodesejado; ?></td> <br>
          <td class="listar-livro-ano-desejados"><?php echo $anodesejado;?></td>
          <td class="listar-livro-genero-desejados"><?php echo $generodesejado;?></td>
          <input type='hidden' name="codigolivrodesejado" id="codigolivrodesejado" value="<?php echo $codigolivrodesejado;?>" >
          <td class="listar-livro-alterar-desejados"><input type="submit" name="Ver"  id="Ver"   value="Alterar" /></td>
          <td class="listar-livro-deletar-desejados"><input type="submit" name="Ver"  id="Ver"   value="Excluir" /></td>
      </tr>
      </table>
      </h5>
    </form>

    <?php } ?>

    <h2 class="Solicitacoes">Solicitações</h2>
 
    <?php
      while ($linhasolicitacao=mysql_fetch_array($query10)){
        $codigosolicitacao = $linhasolicitacao["N_COD_TROCA"];
        $foto_solicitado = $linhasolicitacao["FOTO_SOLICITADO"];
        $foto_solicitante= $linhasolicitacao['FOTO_SOLICITANTE']; 
        $usuario_solicitado= $linhasolicitacao['NOME_SOLICITADO']; 
        $usuario_solicitante= $linhasolicitacao['NOME_SOLICITANE']; 
    ?>



    <form id="form2" name="form2" method="post" action="">
      <h5 class="listar-solicitacoes">
      <table class="table-listar-solicitacoes">
        <tr class="listar-solicitacoes">
          <td class="listar-solicitacoes-foto-solicitado"><img src="<?php echo $foto_solicitado; ?>"width="50" height="50"></td>
          <td class="listar-solicitacoes-foto-troca"><img src="Imagens/Troca.png"width="50" height="50"></td>
          <td class="listar-solicitacoes-foto-solicitante"><img src="<?php echo $foto_solicitante; ?>"width="50" height="50"></td>
          <td class="listar-livro-solicitacoes-usuario-solicitado"><?php echo $usuario_solicitado;?></td>
          <td class="listar-livro-solicitacoes-usuario-solicitante"><?php echo $usuario_solicitante;?></td>
          <input type='hidden' name="codigosolicitacao" id="codigosolicitacao" value="<?php echo $codigosolicitacao;?>" >
          <td class="listar-livro-solicitacoes-aceitar"><input type="submit" name="Ver"  id="Ver"   value="Aceitar" /></td>
          <td class="listar-livro-solicitacoes-recusar"><input type="submit" name="Ver"  id="Ver"   value="Recusar" /></td>
      </tr>
      </table>
      </h5>
    </form>

    <?php } ?>


    </div>
  </div>

   <?php include("../Views/View_rodape.php"); ?>
</body>
</html>



<?php
require_once "../Dados/Conexao.php";
$logado = $_SESSION['login'];
$codigo = $_SESSION['codigo'];
$tipo = $_SESSION['tipo'];

if (@$_GET['go'] == 'salvarfoto') {
  $error;
  // Recupera os dados dos campos
  $foto = $_FILES["foto"];

  // Se a foto estiver sido selecionada
  if (!empty($foto["name"])) {
    
    // Largura máxima em pixels
    $largura = 1920;
    // Altura máxima em pixels
    $altura = 1080;
    // Tamanho máximo do arquivo em bytes
    $tamanho = 1600000;
 
      // Verifica se o arquivo é uma imagem
      if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
         $error[1] = "Isso não é uma imagem.";
      } 
  
    // Pega as dimensões da imagem
    $dimensoes = getimagesize($foto["tmp_name"]);
  
    // Verifica se a largura da imagem é maior que a largura permitida
    if($dimensoes[0] > $largura) {
      $error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
    }
 
    // Verifica se a altura da imagem é maior que a altura permitida
    if($dimensoes[1] > $altura) {
      $error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
    }
    
    // Verifica se o tamanho da imagem é maior que o tamanho permitido
    if($foto["size"] > $tamanho) {
        $error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
    }
 

    // Se não houver nenhum erro
    if (count($error) == 0) {
    
      // Pega extensão da imagem
      preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
 
          // Gera um nome único para a imagem
          $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
 
          // Caminho de onde ficará a imagem
          $caminho_imagem = "FotoPerfilUsuario/" . $nome_imagem;
 
      // Faz o upload da imagem para seu respectivo caminho
      move_uploaded_file($foto["tmp_name"], $caminho_imagem);
      
      // Insere os dados no banco
      $sql = mysql_query("UPDATE usuario SET V_FOTO = '".$caminho_imagem."' WHERE N_COD_USUARIO = $codigo");   
      
      // Se os dados forem inseridos com sucesso
      if (!$sql){
        echo "<script>alert('Usuário e senha não correspondem, tente novamente !! '); history.back();</script>";
      }else{
        echo "<meta http-equiv='refresh' content='0, url=PerfilUsuario.php'>"; 
      }
    }
  
    // Se houver mensagens de erro, exibe-as
    if (count($error) != 0) {
      foreach ($error as $erro) {
        echo $erro . "<br />";
        echo "<script>alert('".$erro."!! '); history.back();</script>";
      }
    }
  }

}
?>