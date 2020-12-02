/**
 * Helpers
 */


// get single element by CSS selector
const _ = ( selector ) => document.querySelector( selector );


// get all elements by CSS selector
const __ = ( selector ) => document.querySelectorAll( selector );

 
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


// simple email validation
const isValidEmail = ( email ) => {

	const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return re.test( email );
	
}


// escape HTML special chars to prevent XSS
String.prototype.escape = function()
{

    const specialChars = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;'
	};
	
    return this.replace( /[&<>]/g, ( tag ) => specialChars[tag] || tag );
	
};