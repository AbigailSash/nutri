import Navbar from "../../components/navigation/Navbar";
import Layout from "../../hocs/layouts/Layout";
import Footer from "../../components/navigation/Footer";
import HeroSection from "../../components/landing/HeroSection";
import ServicesSection from "../../components/landing/ServicesSection";
import BenefitsSection from "../../components/landing/BenefitsSection";
import NutritionistProfile from "../../components/landing/NutritionistProfile";
import TestimonialsSection from "../../components/landing/TestimonialsSection";
import FAQSection from "../../components/landing/FAQSection";
import ContactSection from "../../components/landing/ContactSection";
import WhatsAppButton from "../../components/landing/WhatsAppButton";

function Home() {
    return(
        <Layout>
            <Navbar />
            <HeroSection />
            <ServicesSection />
            <BenefitsSection />
            <NutritionistProfile />
            <TestimonialsSection />
            <FAQSection />
            <ContactSection />
            <Footer />
            <WhatsAppButton />
        </Layout>
    )
}
export default Home;