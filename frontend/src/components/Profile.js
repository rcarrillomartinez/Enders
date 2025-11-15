import React, { useState, useContext } from "react";
import { UserContext } from "../context/UserContext";

const Profile = () => {
  const { user, setUser } = useContext(UserContext);
  const [form, setForm] = useState({
    nombre: user?.nombre || "",
    email: user?.email || "",
  });
  const [message, setMessage] = useState(null);

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = (e) => {
    e.preventDefault();
    // Aquí llamarías a la API para actualizar el perfil
    setUser({ ...user, nombre: form.nombre, email: form.email });
    setMessage("Perfil actualizado");
  };

  if (!user) return <p>Debes iniciar sesión para ver tu perfil</p>;

  return (
    <div className="perfil-container">
      <h3>Perfil de Usuario</h3>
      <form onSubmit={handleSubmit}>
        <label>Nombre</label>
        <input name="nombre" value={form.nombre} onChange={handleChange} required />
        
        <label>Email</label>
        <input name="email" type="email" value={form.email} onChange={handleChange} required />
        
        <button className="primary-button" type="submit">Actualizar</button>
        {message && <div className="success-message">{message}</div>}
      </form>
    </div>
  );
};

export default Profile;
