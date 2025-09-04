function ContactSection() {
    return (
        <section className="py-16" style={{background: 'linear-gradient(to bottom right, #b39ddb, #9575cd)'}}>
            <div className="container mx-auto px-4">
                <div className="max-w-6xl mx-auto">
                    <div className="text-center mb-12">
                        <h2 className="text-4xl font-bold text-white mb-4">
                            ¡Comienza Tu Transformación Hoy!
                        </h2>
                        <p className="text-xl text-white opacity-90 max-w-3xl mx-auto">
                            Da el primer paso hacia una vida más saludable. Contáctanos y agenda tu consulta inicial.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        
                        {/* Información de contacto */}
                        <div className="text-white space-y-8">
                            <div className="flex items-center space-x-4">
                                <div className="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-semibold text-lg">Ubicación</h3>
                                    <p className="opacity-90">Buenos Aires, Argentina</p>
                                    <p className="opacity-75">Consultas presenciales y virtuales</p>
                                </div>
                            </div>

                            <div className="flex items-center space-x-4">
                                <div className="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-semibold text-lg">WhatsApp</h3>
                                    <p className="opacity-90">+54 9 11 2345-6789</p>
                                    <p className="opacity-75">Lun a Vie: 9:00 - 18:00</p>
                                </div>
                            </div>

                            <div className="flex items-center space-x-4">
                                <div className="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-semibold text-lg">Email</h3>
                                    <p className="opacity-90">leila.olmedo@nutrisalud.com</p>
                                    <p className="opacity-75">Respuesta en 24hs</p>
                                </div>
                            </div>

                            <div className="flex items-center space-x-4">
                                <div className="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 className="font-semibold text-lg">Horarios</h3>
                                    <p className="opacity-90">Lunes a Viernes: 9:00 - 19:00</p>
                                    <p className="opacity-75">Sábados: 9:00 - 13:00</p>
                                </div>
                            </div>
                        </div>

                        {/* Formulario de contacto */}
                        <div className="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl">
                            <h3 className="text-2xl font-bold text-gray-800 dark:text-white mb-6">
                                Solicita tu Consulta
                            </h3>
                            
                            <form className="space-y-4">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Nombre *
                                        </label>
                                        <input 
                                            type="text" 
                                            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        style={{'--tw-ring-color': '#9575cd'}}
                                        onFocus={e => e.target.style.borderColor = '#9575cd'}
                                        onBlur={e => e.target.style.borderColor = '#d1d5db'} 
                                            placeholder="Tu nombre"
                                            required 
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Apellido *
                                        </label>
                                        <input 
                                            type="text" 
                                            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        style={{'--tw-ring-color': '#9575cd'}}
                                        onFocus={e => e.target.style.borderColor = '#9575cd'}
                                        onBlur={e => e.target.style.borderColor = '#d1d5db'} 
                                            placeholder="Tu apellido"
                                            required 
                                        />
                                    </div>
                                </div>
                                
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email *
                                    </label>
                                    <input 
                                        type="email" 
                                        className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        style={{'--tw-ring-color': '#9575cd'}}
                                        onFocus={e => e.target.style.borderColor = '#9575cd'}
                                        onBlur={e => e.target.style.borderColor = '#d1d5db'} 
                                        placeholder="tu@email.com"
                                        required 
                                    />
                                </div>
                                
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Teléfono
                                    </label>
                                    <input 
                                        type="tel" 
                                        className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        style={{'--tw-ring-color': '#9575cd'}}
                                        onFocus={e => e.target.style.borderColor = '#9575cd'}
                                        onBlur={e => e.target.style.borderColor = '#d1d5db'} 
                                        placeholder="+54 9 11 1234-5678"
                                    />
                                </div>
                                
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        ¿Cuál es tu objetivo? *
                                    </label>
                                    <select className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        style={{'--tw-ring-color': '#9575cd'}}
                                        onFocus={e => e.target.style.borderColor = '#9575cd'}
                                        onBlur={e => e.target.style.borderColor = '#d1d5db'}
                                        required>
                                        <option value="">Selecciona tu objetivo</option>
                                        <option value="perdida_peso">Pérdida de peso</option>
                                        <option value="ganancia_peso">Ganancia de peso</option>
                                        <option value="mantenimiento">Mantenimiento saludable</option>
                                        <option value="deportiva">Nutrición deportiva</option>
                                        <option value="clinica">Nutrición clínica</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Mensaje
                                    </label>
                                    <textarea 
                                        rows="4" 
                                        className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        style={{'--tw-ring-color': '#9575cd'}}
                                        onFocus={e => e.target.style.borderColor = '#9575cd'}
                                        onBlur={e => e.target.style.borderColor = '#d1d5db'} 
                                        placeholder="Cuéntanos más sobre tus objetivos, restricciones alimentarias o cualquier información relevante..."
                                    ></textarea>
                                </div>
                                
                                <button 
                                    type="submit" 
                                    className="w-full text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300"
                                    style={{backgroundColor: '#b39ddb'}}
                                    onMouseEnter={e => e.target.style.backgroundColor = '#9575cd'}
                                    onMouseLeave={e => e.target.style.backgroundColor = '#b39ddb'}
                                >
                                    Enviar Solicitud
                                </button>
                                
                                <p className="text-sm text-gray-600 dark:text-gray-400 text-center">
                                    Te contactaremos en menos de 24 horas para coordinar tu consulta inicial.
                                </p>
                            </form>
                        </div>
                    </div>

                    {/* Redes sociales */}
                    <div className="text-center mt-12 pt-8 border-t border-white border-opacity-20">
                        <h3 className="text-white text-lg font-semibold mb-4">Síguenos en redes sociales</h3>
                        <div className="flex justify-center space-x-4">
                            <a href="#" className="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white hover:bg-opacity-30 transition-colors">
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" className="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white hover:bg-opacity-30 transition-colors">
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" className="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white hover:bg-opacity-30 transition-colors">
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.219.085.344-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                            <a href="#" className="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white hover:bg-opacity-30 transition-colors">
                                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}

export default ContactSection;