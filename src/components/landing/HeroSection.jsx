import { Link } from 'react-router-dom';

function HeroSection() {
    return (
        <section className="py-16 text-center bg-gray-50">
            <div className="container mx-auto px-4">
                <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Transforma tu salud con <span className="text-green-600">NutriSalud</span>
                </h1>
                <p className="text-lg md:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Planes alimentarios personalizados, seguimiento clínico y cálculos nutricionales en un solo lugar.
                </p>
                
                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link 
                        to="/auth/login" 
                        className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300"
                    >
                        Ingreso al Sistema
                    </Link>
                </div>
            </div>
        </section>
    );
}

export default HeroSection;