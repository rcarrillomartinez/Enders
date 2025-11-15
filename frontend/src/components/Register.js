import React, { useState } from "react";
import { registerUser } from "../services/api";

const Register = () => {
  const [form, setForm] = useState({ email: "", password: "", nombre: "", rol: "particular" });
  const [message, setMessage] = useState(null);

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });
  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await registerUser(form);
    if (result.success) setMessage("Registro exitoso.");
    else setMessage("Error en el registro.");
  };

  return (
    <div className="container">
      <form onSubmit={handleSubmit} className="form-card">
        <h2>Registrar usuario</h2>

        <label htmlFor="email">Correo electrónico</label>
        <input
          id="email"
          name="email"
          type="email"
          value={form.email}
          onChange={handleChange}
          placeholder="ejemplo@correo.com"
          required
        />

        <label htmlFor="password">Contraseña</label>
        <input
          id="password"
          name="password"
          type="password"
          value={form.password}
          onChange={handleChange}
          placeholder="Mínimo 8 caracteres"
          required
        />

        <label htmlFor="nombre">Nombre completo</label>
        <input
          id="nombre"
          name="nombre"
          type="text"
          value={form.nombre}
          onChange={handleChange}
          placeholder="Tu nombre y apellidos"
          required
        />

        <label htmlFor="rol">Tipo de usuario</label>
        <select id="rol" name="rol" value={form.rol} onChange={handleChange}>
          <option value="administrador">Administrador</option>
          <option value="particular">Particular</option>
          <option value="corporativo">Corporativo</option>
        </select>

        <button type="submit" className="primary-button">Registrar</button>

        {message && <div className="success-message">{message}</div>}
      </form>
    </div>
  );
};

export default Register;
