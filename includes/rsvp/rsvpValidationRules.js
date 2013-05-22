$().ready(function() {
     $("#rsvp-form").validate({
          rules: {
               guestName: {
                required: true,
                maxlength: 30
               },
               staffYears: {
                required: true,
                maxlength: 60
               },
               email: {
                required: true,
                email: true,
                maxlength: 60
               },
               phone: {
                required: true,
                minlength: 12
               },
               streetAddress: {
                required: true,
                maxlength: 60
               },
               city: {
                required: true,
                maxlength: 60
               },
               state: {
                required: true,
                maxlength: 60
               },
               zipcode: {
                required: true,
                maxlength: 12
               },
               required: {
                required: false
               }
          },
          messages: {
               guestName: "< Please enter your name.",
               staffYears: "< Please enter the years you were on staff.",
               email: "< Please enter a valid email.",
               phone: "< Please enter a valid phone number.",
               streetAddress: "< Please enter your address.",
               city: "< Please enter your city.",
               state: "< Please enter your state.",
               zipcode: "< Please enter a valid zipcode."
          }
     });
})
