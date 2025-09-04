function ServicesSection() {
    const services = [
        {
            image: "🔍",
            title: "Evaluación Nutricional",
            description: "Mediciones antropométricas completas y cálculo de indicadores como IMC e ICC."
        },
        {
            image: "📋",
            title: "Plan Alimentario", 
            description: "Dietas personalizadas con objetivos claros: salud, rendimiento o bienestar."
        },
        {
            image: "📈",
            title: "Seguimiento Continuo",
            description: "Control de progreso con gráficas, reportes y sugerencias adaptativas."
        }
    ];

    return (
        <section id="servicios" className="py-16 bg-white">
            <div className="container mx-auto px-4">
                <h2 className="text-3xl font-bold text-center text-gray-800 mb-12">
                    Nuestros Servicios
                </h2>
                
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    {services.map((service, index) => (
                        <div key={index} className="flex flex-col items-center">
                            <div className="text-6xl mb-4">
                                {service.image}
                            </div>
                            <h5 className="text-xl font-semibold text-gray-800 mb-3">
                                {service.title}
                            </h5>
                            <p className="text-gray-600 leading-relaxed">
                                {service.description}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}

export default ServicesSection;