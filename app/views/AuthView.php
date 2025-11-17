<h2><?php echo $page === 'signup' ? 'Registro' : 'Login'; ?></h2>
<?php if(!empty($result['message'])): ?>
    <p style="color:green"><?php echo $result['message']; ?></p>
<?php endif; ?>
<?php if(!empty($result['error'])): ?>
    <p style="color:red"><?php echo $result['error']; ?></p>
<?php endif; ?>

<?php if($page === 'signup'): ?>
<form method="post" action="?action=register">
    <label>Nombre: <input name="nombre"></label><br>
    <label>Apellido1: <input name="apellido1"></label><br>
    <label>Apellido2: <input name="apellido2"></label><br>
    <label>Email: <input name="email"></label><br>
    <label>Contraseña: <input type="password" name="password"></label><br>
    <button>Registrar</button>
</form>
<?php else: ?>
<form method="post" action="?action=login">
    <label>Email: <input name="email"></label><br>
    <label>Contraseña: <input type="password" name="password"></label><br>
    <button>Entrar</button>
</form>
<?php endif; ?>
