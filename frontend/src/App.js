import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./components/Login";
import Register from "./components/Register";
import PanelAdmin from "./components/PanelAdmin";
import PanelUser from "./components/PanelUser";
import ReservaForm from "./components/ReservaForm";
import Profile from "./components/Profile";
import { UserProvider } from "./context/UserContext";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";

function App() {
  return (
    <UserProvider>
      <BrowserRouter>
        <Navbar />
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/admin" element={<PanelAdmin />} />
          <Route path="/usuario" element={<PanelUser />} />
          <Route path="/reserva" element={<ReservaForm />} />
          <Route path="/perfil" element={<Profile />} />
        </Routes>
        <Footer />
      </BrowserRouter>
    </UserProvider>
  );
}

export default App;
