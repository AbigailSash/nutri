import { AUTH_ACTIONS } from '../actions/auth';

const initialState = {
  isAuthenticated: false,
  user: null,
  access_token: null,
  refresh_token: null,
  loading: false,
  error: null,
};

const authReducer = (state = initialState, action) => {
  switch (action.type) {
    case AUTH_ACTIONS.AUTH_LOADING:
      return {
        ...state,
        loading: true,
        error: null,
      };

    case AUTH_ACTIONS.LOGIN_SUCCESS:
    case AUTH_ACTIONS.USER_LOADED:
      return {
        ...state,
        isAuthenticated: true,
        user: action.payload.user,
        access_token: action.payload.access_token,
        refresh_token: action.payload.refresh_token,
        loading: false,
        error: null,
      };

    case AUTH_ACTIONS.LOGIN_FAIL:
    case AUTH_ACTIONS.AUTH_ERROR:
      return {
        ...state,
        isAuthenticated: false,
        user: null,
        access_token: null,
        refresh_token: null,
        loading: false,
        error: action.payload,
      };

    case AUTH_ACTIONS.LOGOUT:
      return {
        ...initialState,
      };

    case AUTH_ACTIONS.CLEAR_ERROR:
      return {
        ...state,
        error: null,
      };

    default:
      return state;
  }
};

export default authReducer;