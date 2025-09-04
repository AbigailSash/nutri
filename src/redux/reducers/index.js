
import { combineReducers } from "redux";
import authReducer from "./auth";

function appReducer(state = { ready: true }, action) {
  switch (action.type) {
    default:
      return state;
  }
}

const rootReducer = combineReducers({
  app: appReducer,
  auth: authReducer,
});

export default rootReducer;
