import React, { useState, useEffect } from "react";

const PanelAdmin = () => {
  const [reservas, setReservas] = useState([]);
  const [filtro, setFiltro] = useState("dia");
  const [valor, setValor] = useState(new Date().toISOString().slice(0, 10));

  useEffect(() => {
    fetch(`${process.env.REACT_APP_API_URL}?module=reserva&action=listar&filtro=${filtro}&valor=${valor}`)
      .then(res => res.json())
      .then(setReservas)
      .catch(console.error);
  }, [filtro, valor]);

  return (
    <main className="panel-container">
      <h1>Panel de Administración</h1>
      <div>
        <label>Filtrar por:</label>
        <select value={filtro} onChange={e => setFiltro(e.target.value)}>
          <option value="dia">Día</option>
          <option value="semana">Semana</option>
          <option value="mes">Mes</option>
        </select>
        <input type="date" value={valor} onChange={e => setValor(e.target.value)} />
      </div>
      <section>
        {(reservas.length === 0) ? (
          <p>No hay reservas para mostrar</p>
        ) : (
          reservas.map(r => (
            <div key={r.id_reserva} className="reserva-card">
              <h4>Reserva: {r.localizador}</h4>
              <p>Hotel: {r.id_hotel}</p>
              <p>Fecha: {r.fecha_reserva}</p>
              <p>Viajeros: {r.num_viajeros}</p>
            </div>
          ))
        )}
      </section>
    </main>
  );
};

export default PanelAdmin;
