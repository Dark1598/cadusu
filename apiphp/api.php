<?php

include_once('conexao.php');
 $postjson =  json_decode(file_get_contents('php://input'),true);

 if($postjson['requisicao'] == 'login'){
    $query = $pdo->query("SELECT * from usuarios where email = '$postjson[email]' and senha = '$postjson[senha]'");
    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
    for ($i=0; $i < count($resultado); $i++){
        foreach ($resultado[$i] as $key => $value) {
        }
            $dados = array(
                'id'=> $resultado[$i]['id'],
                'nome'=> $resultado[$i]['nome'],
                'email'=> $resultado[$i]['email'],
                'senha'=> $resultado[$i]['senha'],
                'nivel'=> $resultado[$i]['nivel'],
            );
        }
    
    if(count($resultado)>0){
        $resultado_para = json_encode(array('success'=>true,'result'=>$dados));
    }
    else {
        $resultado_para = json_encode(array('success'=>false, 'msg'=>'Dados incorretos para acesso do TI88'));
    }
    echo $resultado_para;
}
 else if($postjson['requisicao'] == 'cadastrar'){
    $query = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, senha = :senha, nivel = :nivel ");
    $query->bindValue(':nome', $postjson['nome']);
    $query->bindValue(':email', $postjson['email']);
    $query->bindValue(':senha', md5($postjson['senha']));
    $query->bindValue(':nivel', $postjson['nivel']);
    $query->execute();

    $id = $pdo->lastInsertId();

    if($query){
        $res = json_encode(array('success'=>true,'id'=>$id));
    }else{
        $res = json_encode(array('success'=>false));
    }
    echo $res;


}

else if($postjson['requisicao']=='listar'){
    if($postjson['nome']==''){// buscar todos
        $query = $pdo->query("SELECT * FROM usuarios order by id desc limit $postjson[start],$postjson[limit] ");
    }else{
        $busca = $postjson['nome'].'%';
        $query = $pdo->query("SELECT * FROM usuarios where nome LIKE '$busca' or email LIKE '$busca' order by id desc limit $postjson[start],$postjson[limit] ");   
    }

    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

    for ($i=0; $i < count($resultado); $i++){
        foreach ($resultado[$i] as $key => $value) {
        }
            $dados[] = array(
                'id'=> $resultado[$i]['id'],
                'nome'=> $resultado[$i]['nome'],
                'email'=> $resultado[$i]['email'],
                'nivel'=> $resultado[$i]['nivel'],
            );
        }
    if(count($resultado) > 0){
        $result = json_encode(array('success'=>true, 'result'=>$dados));
    }else{
        $result = json_encode(array('success'=>false, 'result'=>0));
    }
    echo $result;
} // final do método para listar
else if($postjson['requisicao']=='editar'){
    $query = $pdo->prepare("UPDATE usuarios SET nome=:nome, email = :email, senha = :senha, nivel=:nivel where id = :id");

    $query->bindValue(':nome', $postjson['nome']);
    $query->bindValue(':email', $postjson['email']);
    $query->bindValue(':senha', md5($postjson['senha']));
    $query->bindValue(':nivel', $postjson['nivel']);    
    $query->bindValue(':id', $postjson['id']);
    $query->execute();
    if($query){
        $result = json_encode(array('success'=>true));
    }else{
        $result = json_encode(array('success'=>false, 'result'=>0));
    }
    echo $result;
}
else if($postjson['requisicao']=='excluir'){
    $query = $pdo->query("DELETE FROM usuarios where id = '$postjson[id]'");
    if($query){
        $result = json_encode(array('success'=>true));
    }else{
        $result = json_encode(array('success'=>false));
    }
    echo $result;
}

?>