import { Link } from 'react-router-dom';
import { useSelector } from 'react-redux';
import Layout from '../../hocs/layouts/Layout';

function Unauthorized() {
    const { user } = useSelector(state => state.auth);

    return (
        <Layout>
            <div className="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-md">
                    <div className="mx-auto flex justify-center">
                        <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                            <svg className="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fillRule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clipRule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Acceso Denegado
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        No tienes permisos para acceder a esta página
                    </p>
                </div>

                <div className="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                    <div className="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                        <div className="text-center">
                            <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L4.312 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <h3 className="mt-2 text-sm font-medium text-gray-900">Sin autorización</h3>
                            <p className="mt-1 text-sm text-gray-500">
                                Tu cuenta ({user?.role}) no tiene permisos para acceder a esta sección.
                            </p>
                            <div className="mt-6 flex flex-col space-y-3">
                                <Link
                                    to={user?.role === 'nutricionista' ? '/dashboard/nutri' : user?.role === 'paciente' ? '/dashboard/paciente' : '/'}
                                    className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    style={{backgroundColor: '#b39ddb', '--tw-ring-color': '#9575cd'}}
                                    onMouseEnter={e => e.target.style.backgroundColor = '#9575cd'}
                                    onMouseLeave={e => e.target.style.backgroundColor = '#b39ddb'}
                                >
                                    Ir a mi dashboard
                                </Link>
                                <Link
                                    to="/"
                                    className="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    style={{'--tw-ring-color': '#9575cd'}}
                                >
                                    Volver al inicio
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default Unauthorized;