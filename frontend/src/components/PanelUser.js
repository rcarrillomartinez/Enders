import React, { useContext, useEffect, useState } from "react";
import { UserContext } from "../context/UserContext";

const PanelUser = () => {
  const { user } = useContext(UserContext);
  const [reservas, setReservas] = useState([]);

  useEffect(() => {
    if (user?.id_usuario) {
      fetch(`http://localhost:8000/index.php?module=reserva&action=listarPorUsuario&id_usuario=${user.id_usuario}`)
        .then(res => res.json())
        .then(data => {
          // Ajustar según formato real de backend
          // Si recibes un objeto con array dentro de 'data', por ejemplo, usar data.data
          const arr = Array.isArray(data) ? data : Array.isArray(data.data) ? data.data : [];
          setReservas(arr);
        })
        .catch(console.error);
    }
  }, [user]);

  if (!user) return <p>Por favor, inicia sesión para ver tus reservas.</p>;

  return (
    <main>
      <h1>Bienvenido {user.nombre}</h1>
      <h2>Mis Reservas</h2>
      {reservas.length === 0 ? (
        <p>No tienes reservas aún.</p>
      ) : (
        reservas.map(reserva => (
          <div key={reserva.id_reserva}>
            <h4>Reserva {reserva.localizador}</h4>
            <p>Hotel: {reserva.id_hotel}</p>
            <p>Fecha: {reserva.fecha_reserva}</p>
          </div>
        ))
      )}
    </main>
  );
};

export default PanelUser;
