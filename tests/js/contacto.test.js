QUnit.test("Validaciones del formulario de contacto: Nombre debe tener al menos 3 caracteres", function(assert) {
    assert.notOk(validarNombre("Jo"), "Nombre 'Jo' es inválido");
    assert.ok(validarNombre("John"), "Nombre 'John' es válido");
});

QUnit.test("Validaciones del formulario de contacto: Correo debe tener formato válido", function(assert) {
    assert.notOk(validarCorreo("correo_invalido"), "Correo sin @ es inválido");
    assert.notOk(validarCorreo("correo@"), "Correo sin dominio es inválido");
    assert.ok(validarCorreo("correo@example.com"), "Correo válido");
});

QUnit.test("Validaciones del formulario de contacto: Mensaje debe tener al menos 10 caracteres", function(assert) {
    assert.notOk(validarMensaje("Hola"), "Mensaje 'Hola' es inválido");
    assert.ok(validarMensaje("Este es un mensaje válido"), "Mensaje con más de 10 caracteres es válido");
});
