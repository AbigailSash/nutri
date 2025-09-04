import { connect } from 'react-redux'
import { Link } from 'react-router-dom';
import { useState } from 'react';

function Navbar() {
    const [isOpen, setIsOpen] = useState(false);
    const [darkMode, setDarkMode] = useState(false);

    const toggleDarkMode = () => {
        setDarkMode(!darkMode);
        document.body.classList.toggle('dark-mode');
    };

    return(
        <nav className='w-full py-2 shadow-sm fixed top-0 z-50 bg-white'>
            <div className="container mx-auto px-4">
                <div className="flex items-center justify-between h-14">
                    {/* Logo */}
                    <div className="flex items-center">
                        <Link to="/" className="flex items-center">
                            <div className="w-10 h-10 bg-green-600 rounded flex items-center justify-center mr-3">
                                <span className="text-white font-bold text-sm">N</span>
                            </div>
                            <span className="text-xl font-bold text-gray-800">
                                NutriSalud
                            </span>
                        </Link>
                    </div>
                    
                    {/* Desktop Menu */}
                    <div className="hidden md:flex items-center space-x-6">
                        <a href="#servicios" className="text-gray-700 hover:text-green-600 transition-colors">
                            Servicios
                        </a>
                        <a href="#beneficios" className="text-gray-700 hover:text-green-600 transition-colors">
                            Beneficios
                        </a>
                        <a href="#testimonios" className="text-gray-700 hover:text-green-600 transition-colors">
                            Testimonios
                        </a>
                        <Link
                            to="/auth/login"
                            className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium transition-colors"
                        >
                            Login
                        </Link>
                        
                        {/* Dark mode toggle */}
                        <button
                            onClick={toggleDarkMode}
                            className="p-2 rounded border border-gray-300 hover:bg-gray-100 transition-colors"
                            aria-label="Toggle dark mode"
                        >
                            <svg className="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                        </button>
                    </div>

                    {/* Mobile menu button */}
                    <div className="md:hidden">
                        <button
                            onClick={() => setIsOpen(!isOpen)}
                            className="p-2 rounded-md text-gray-700 hover:text-green-600 hover:bg-gray-100"
                        >
                            <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                {isOpen ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                )}
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Mobile Menu */}
                {isOpen && (
                    <div className="md:hidden">
                        <div className="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                            <a
                                href="#servicios"
                                className="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors"
                                onClick={() => setIsOpen(false)}
                            >
                                Servicios
                            </a>
                            <a
                                href="#beneficios"
                                className="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors"
                                onClick={() => setIsOpen(false)}
                            >
                                Beneficios
                            </a>
                            <a
                                href="#testimonios"
                                className="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors"
                                onClick={() => setIsOpen(false)}
                            >
                                Testimonios
                            </a>
                            <Link
                                to="/auth/login"
                                className="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold transition-colors mt-4"
                                onClick={() => setIsOpen(false)}
                            >
                                Login
                            </Link>
                        </div>
                    </div>
                )}
            </div>
        </nav>
        )    
}

    const mapStateToProps = () => ({ });
    

    export default connect(mapStateToProps,{
    })(Navbar);