import React from "react";

const Alert = ({ message, tipo }) => {
  if (!message) return null;
  const estilos = tipo === "error" ? "error-message" : "success-message";
  return <div className={estilos}>{message}</div>;
};

export default Alert;
