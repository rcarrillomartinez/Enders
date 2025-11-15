import React, { useContext, useState } from "react";
import { createReservation } from "../services/api";
import { UserContext } from "../context/UserContext";

const ReservaForm = () => {
  const { user } = useContext(UserContext);
  const [form, setForm] = useState({
    id_hotel: "",
    id_tipo_reserva: "",
    email_cliente: "",
    fecha_entrada: "",
    hora_entrada: "",
    numero_vuelo_entrada: "",
    origen_vuelo_entrada: "",
    hora_vuelo_salida: "",
    fecha_vuelo_salida: "",
    num_viajeros: "",
    id_vehiculo: ""
  });

  const [message, setMessage] = useState(null);

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!user) {
      setMessage("Debe iniciar sesión para reservar.");
      return;
    }
    const result = await createReservation({ ...form, id_usuario_sistema: user.id_usuario });
    if (result.success) setMessage("Reserva creada con éxito.");
    else setMessage("Error al crear reserva.");
  };

  return (
    <div className="container">
      <form onSubmit={handleSubmit} className="form-card">
        <h2>Crear Reserva</h2>
        <input
          name="email_cliente"
          value={form.email_cliente}
          placeholder="Correo electrónico del cliente"
          onChange={handleChange}
          required
        />
        <input
          name="fecha_entrada"
          type="date"
          value={form.fecha_entrada}
          placeholder="Fecha de entrada"
          onChange={handleChange}
          required
        />
        <input
          name="hora_entrada"
          type="time"
          value={form.hora_entrada}
          placeholder="Hora de entrada"
          onChange={handleChange}
          required
        />
        <input
          name="numero_vuelo_entrada"
          value={form.numero_vuelo_entrada}
          placeholder="Número de vuelo de entrada"
          onChange={handleChange}
          required
        />
        <input
          name="origen_vuelo_entrada"
          value={form.origen_vuelo_entrada}
          placeholder="Origen del vuelo de entrada"
          onChange={handleChange}
          required
        />
        <input
          name="hora_vuelo_salida"
          type="time"
          value={form.hora_vuelo_salida}
          placeholder="Hora del vuelo de salida"
          onChange={handleChange}
          required
        />
        <input
          name="fecha_vuelo_salida"
          type="date"
          value={form.fecha_vuelo_salida}
          placeholder="Fecha del vuelo de salida"
          onChange={handleChange}
          required
        />
        <input
          name="num_viajeros"
          type="number"
          value={form.num_viajeros}
          placeholder="Número de viajeros"
          onChange={handleChange}
          required
        />
        <input
          name="id_hotel"
          value={form.id_hotel}
          placeholder="ID del hotel"
          onChange={handleChange}
          required
        />
        <input
          name="id_vehiculo"
          value={form.id_vehiculo}
          placeholder="ID del vehículo"
          onChange={handleChange}
          required
        />
        <input
          name="id_tipo_reserva"
          value={form.id_tipo_reserva}
          placeholder="ID del tipo de reserva"
          onChange={handleChange}
          required
        />
        <button className="primary-button" type="submit">Enviar reserva</button>
        {message && <div className="success-message">{message}</div>}
      </form>
    </div>
  );
};

export default ReservaForm;
