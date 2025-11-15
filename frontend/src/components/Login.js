import React, { useContext, useState } from "react";
import { useNavigate } from "react-router-dom";
import { UserContext } from "../context/UserContext";

const Login = () => {
  const { setUser } = useContext(UserContext);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await fetch('http://localhost:8000/index.php?module=auth&action=login', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });
      const data = await res.json();
      if (data.success) {
        setUser(data.user);
        navigate("/usuario");
      } else {
        setError(data.error || "Login fallido");
      }
    } catch (e) {
      setError("Error de conexión");
    }
  };

  return (
    <form onSubmit={handleSubmit} className="login-form">
      <h2 className="login-title">Login</h2>
      <input
        type="email"
        required
        placeholder="Email"
        value={email}
        className="input-field"
        onChange={e => setEmail(e.target.value)}
      />
      <input
        type="password"
        required
        placeholder="Contraseña"
        value={password}
        className="input-field"
        onChange={e => setPassword(e.target.value)}
      />
      <button type="submit" className="submit-button">Entrar</button>
      {error && <p className="error-message">{error}</p>}
    </form>
  );
};

export default Login;
