const API_URL = process.env.REACT_APP_API_URL;

const handleResponse = async (res) => {
  if (!res.ok) {
    const text = await res.text();
    let errorMessage = `HTTP error ${res.status}`;
    try {
      const data = JSON.parse(text);
      errorMessage = data.message || JSON.stringify(data);
    } catch {}
    throw new Error(errorMessage);
  }
  return res.json();
};

// services/api.js
export const loginUser = async (email, password) => {
  try {
    const res = await fetch(`http://localhost:8000/index.php?module=auth&action=login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });
    return await res.json();
  } catch (error) {
    return { success: false, error: 'Error de conexión' };
  }
};


export const registerUser = async (data) => {
  const res = await fetch(`${API_URL}?module=usuario&action=crear`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  return handleResponse(res);
};

export const fetchUserReservations = async (userId) => {
  const res = await fetch(`${API_URL}?module=reserva&action=listarPorUsuario&id_usuario=${userId}`);
  return handleResponse(res);
};

export const createReservation = async (data) => {
  const res = await fetch(`${API_URL}?module=reserva&action=crear`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  return handleResponse(res);
};

export const updateReservation = async (id, data) => {
  const res = await fetch(`${API_URL}?module=reserva&action=modificar`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_reserva: id, ...data }),
  });
  return handleResponse(res);
};

export const cancelReservation = async (id) => {
  const res = await fetch(`${API_URL}?module=reserva&action=cancelar`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_reserva: id }),
  });
  return handleResponse(res);
};
