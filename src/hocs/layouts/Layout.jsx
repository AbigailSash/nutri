import { connect } from "react-redux";

function Layout({ children }) {
    return <div>{children}</div>;
}

// función correcta (si necesitas algo del store)
const mapStateToProps = (state) => ({
  // ej: user: state.auth.user
});

// si no mapeas nada, puedes pasar null
export default connect(mapStateToProps /* o null */)(Layout);
