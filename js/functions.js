/**
 * Helpers
 */

 
// convert JSON object to FormData
const jsonToFormData = ( json ) => {

	let formData = new FormData();

	for( let key in json )
	{
		formData.append( key, json[key] );
	}

	return formData;

};


// get cookie by name
const getCookie = ( name ) => {

    let match = document.cookie.match( new RegExp( `(^| )${ name }=([^;]+)` ) );

    return match ? decodeURIComponent( match[2] ) : null;

}