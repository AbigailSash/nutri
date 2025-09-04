import { useSelector } from 'react-redux';
import { Navigate, useLocation } from 'react-router-dom';

function PrivateRoute({ children, allowedRoles = [] }) {
    const { isAuthenticated, user } = useSelector(state => state.auth);
    const location = useLocation();

    if (!isAuthenticated) {
        // Redirigir al login guardando la ruta actual
        return <Navigate to="/auth/login" state={{ from: location }} replace />;
    }

    if (allowedRoles.length > 0 && user && !allowedRoles.includes(user.role)) {
        // Usuario autenticado pero sin permisos para esta ruta
        return <Navigate to="/unauthorized" replace />;
    }

    return children;
}

export default PrivateRoute;