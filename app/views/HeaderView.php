<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservas MVC</title>
    <style>
        body{font-family:Arial, Helvetica, sans-serif;}
        nav a{margin-right:10px;}
    </style>
</head>
<body>
<nav>
    <a href="?action=home">Inicio</a>
    <?php if(!empty($_SESSION['user_id'])): ?>
        <a href="?action=dashboard">Mis reservas</a>
        <a href="?action=perfil">Perfil</a>
        <?php
            $u = \app\models\Auth::getCurrentUser();
            if($u && $u['user_type']=='admin') echo '<a href="?action=admin">Panel Admin</a>';
        ?>
        <a href="?action=logout">Cerrar sesión</a>
    <?php else: ?>
        <a href="?action=auth">Login</a>
        <a href="?action=signup">Registro</a>
    <?php endif; ?>
</nav>
<hr>
