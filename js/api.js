/**
 * Handle API requests
 */


const API_URL = 'api.php';


// make request
const _apiRequest = ({ method = 'GET', data = {}, action = '' }) => {

    let url = `${ API_URL }?action=${ action }`;
    let options = { 

		method, 
		mode: 'cors', 
		redirect: 'follow'

	};

	// if it's GET, add 'data' as query string
	if( method === 'GET' )
	{
		const queryString = Object.keys( data ).map( key => ( key + '=' + data[key] ) ).join( '&' );
		url += queryString ? '&' + queryString : '';
	}

	// other methods, add 'data' as body
	else
	{
		options.body = jsonToFormData( data );
	}


    return fetch( url, options ).then( res => res.json() );

};


// get current logged in user as JSON
const _apiGetCurrentUser = () => {

	const user = JSON.parse( getCookie( 'login' ) );
	return user;

}


// register user
const _apiRegisterUser = ({ email, userName, password, passwordConfirm }) => {

	return _apiRequest({
		method: 'POST',
		action: 'register',
		data: {
			email,
			'user-name': userName,
			password,
			'password-confirm': passwordConfirm
		}
	});

}


// login user
const _apiLoginUser = ({ email, password }) => {

	return _apiRequest({
		method: 'POST',
		action: 'login',
		data: {
			email,
			password
		}
	});

}


// logout user
const _apiLogoutUser = () => {

	return _apiRequest({
		action: 'logout'
	});

}


// set user in online state
const _apiGoOnline = () => {

	return _apiRequest({
		action: 'go-online'
	});

}


// set user in offline state
const _apiGoOffline = () => {

	return _apiRequest({
		action: 'go-offline'
	});

}


// get online users
const _apiGetOnlineUsers = () => {

	return _apiRequest({
		action: 'get-online-users'
	});

}


// get user data by id
const _apiGetUserData = ({ id }) => {

	return _apiRequest({
		action: 'get-user',
		data: {
			'user-id': id
		}
	});

}