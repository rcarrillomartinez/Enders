import React from "react";

const Loader = () => (
  <div className="loader" style={{textAlign:"center", padding:"20px"}}>
    <span role="img" aria-label="loading" style={{fontSize:"3rem"}}>⏳</span>
    <p>Cargando...</p>
  </div>
);

export default Loader;
