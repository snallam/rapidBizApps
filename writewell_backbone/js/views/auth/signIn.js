define([
	'backbone',
	'shared',
	'data',
	'text!templates/auth/signIn.htm'
], function (Backbone, shared, data, txtTemplate) {
	var mainHomeView = Backbone.View.extend({
		initialize: function () {
			this.template = _.template(txtTemplate);
		},
		events: {
			'click #signInBtn': 'signIn',
			'click #forgotPasswordBtn': 'forgotPassword',
		},
		render: function () {
			var self = this;
			data.ensureDemoDataLoaded(function () {
				$(self.el).html(self.template());
			});
		},
		signIn: function () {

            var details={username: $("#email").val(),password:$("#password").val()};
            $.ajax({
                type: "POST",
                url: "http://localhost/writewell_production/public/signin/checklogin",
                data: JSON.stringify(details),
                success: function( result ) {

                    if(result=='1')
                    {

                        shared.router.navigate('projects',true);
                    }
                },
                dataType: 'json'
            }).error(function(error){console.log(error)});

		},

		forgotPassword: function () {
			console.log('clicked forgot password');
		}
	});
	return mainHomeView;
});