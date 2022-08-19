import {User} from "../../models/user";
// Events , we will send user event, we will send to all components
export const setUser = (user: User) => {
    return {
        type: 'SET_USER',
        user
    }
}