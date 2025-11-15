import React, { useContext } from "react";
import { Link } from "react-router-dom";  // Importa Link
import { UserContext } from "../context/UserContext";

const Navbar = () => {
  const { user, setUser } = useContext(UserContext);
  const handleLogout = () => setUser(null);

  return (
    <header>
      <nav className="nav-bar">
        <Link to="/" className="logo">Isla Transfers</Link>  {/* Usa Link */}
        <div className="nav-links">
          {!user ? (
            <>
              <Link to="/login">Entrar</Link>    {/* Usa Link */}
              <Link to="/register">Registrar</Link> {/* Usa Link */}
              <Link to="/reserva">Hacer Reserva</Link>
            </>
          ) : (
            <>
              <span>{user.nombre}</span>
              <button className="logout-button" onClick={handleLogout}>Cerrar sesión</button>
            </>
          )}
        </div>
      </nav>
    </header>
  );
};

export default Navbar;
