import React from "react";

const ReservaList = ({ reservas, onSeleccionar }) => {
  if (!reservas || reservas.length === 0) return <p>No hay reservas disponibles</p>;

  return (
    <ul className="reserva-list">
      {reservas.map((reserva) => (
        <li key={reserva.id_reserva} className="reserva-card" onClick={() => onSeleccionar(reserva)} style={{cursor:"pointer"}}>
          <h4>{reserva.localizador}</h4>
          <p>{reserva.fecha_reserva}</p>
        </li>
      ))}
    </ul>
  );
};

export default ReservaList;
