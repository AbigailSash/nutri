import { Link } from 'react-router-dom';

function HeroSection() {
    return (
        <section className="pt-24 pb-16 text-center" style={{backgroundColor: '#e8ddf5'}}>
            <div className="container mx-auto px-4">
                <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Transforma tu salud con <span style={{color: '#9575cd'}}>NutriSalud</span>
                </h1>
                <p className="text-lg md:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Planes alimentarios personalizados, seguimiento clínico y cálculos nutricionales en un solo lugar.
                </p>
                
                <div className="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link 
                        to="/auth/login" 
                        className="text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300"
                        style={{backgroundColor: '#b39ddb'}}
                        onMouseEnter={e => e.target.style.backgroundColor = '#9575cd'}
                        onMouseLeave={e => e.target.style.backgroundColor = '#b39ddb'}
                    >
                        Ingreso al Sistema
                    </Link>
                </div>
            </div>
        </section>
    );
}

export default HeroSection;