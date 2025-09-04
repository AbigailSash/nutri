import { legacy_createStore as createStore, applyMiddleware, compose } from "redux";
import { thunk } from "redux-thunk"; // o import thunk from "redux-thunk" si usas v3
import rootReducer from "./redux/reducers";
import { composeWithDevTools } from "@redux-devtools/extension";

const composeEnhancers = import.meta.env.DEV ? composeWithDevTools : compose;

const store = createStore(
  rootReducer,                       // <- debe ser una función
  composeEnhancers(applyMiddleware(thunk))
);

export default store;
