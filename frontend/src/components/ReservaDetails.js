import React from "react";

const ReservaDetails = ({ reserva }) => {
  if (!reserva) return <div className="notfound-container">Selecciona una reserva para ver detalles</div>;

  return (
    <div className="reserva-card">
      <h3>Reserva {reserva.localizador}</h3>
      <p>Hotel: {reserva.id_hotel}</p>
      <p>Tipo reserva: {reserva.id_tipo_reserva}</p>
      <p>Fecha entrada: {reserva.fecha_entrada}</p>
      <p>Hora entrada: {reserva.hora_entrada}</p>
      <p>Número vuelo entrada: {reserva.numero_vuelo_entrada}</p>
      <p>Origen vuelo entrada: {reserva.origen_vuelo_entrada}</p>
      <p>Fecha vuelo salida: {reserva.fecha_vuelo_salida}</p>
      <p>Hora vuelo salida: {reserva.hora_vuelo_salida}</p>
      <p>Número viajeros: {reserva.num_viajeros}</p>
    </div>
  );
};

export default ReservaDetails;
