define([
	'backbone',
	'shared',
	'data',
	'text!templates/auth/landing.htm'
], function (Backbone, shared, data, txtTemplate) {
	var mainHomeView = Backbone.View.extend({
		initialize: function () {
			this.template = _.template(txtTemplate);
		},
		events: {
			'click #googleSignInBtn': 'googleSign',
			'click #signUpBtn': 'signUp'
		},
		render: function () {
			var self = this;

			data.ensureDemoDataLoaded(function () {
				$(self.el).html(self.template());
			});
		},
       googleSign: function () {
            var myParams = {
                'clientid' : '220301297262-6echqsehb5gp8nn0oi2kef2a9ffhu52p.apps.googleusercontent.com',
                'cookiepolicy' : 'single_host_origin',
                'callback' : 'loginCallback',
                'approvalprompt':'force',
                'scope' : 'https://www.googleapis.com/auth/drive https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
            };
            gapi.auth.signIn(myParams);
            /*$.ajax(
                {url:"../writewell_production/public/signin/createauthurl",
                    success:function(result){
                        window.location.href = $.parseJSON(result).authurl;
                        //alert($.parseJSON(result).authurl);
                    }});*/


        },
		signUp: function () {
			console.log('clicked sign up');
			shared.router.navigate('signUp',true);
		}
	});
	return mainHomeView;
});