(function ()
{
    var PasswordToggler = function (element, field)
    {
        this.element = element;
        this.field = field;

        this.toggle();
    };

    PasswordToggler.prototype =
            {
                toggle: function ()
                {
                    var self = this;
                    self.element.addEventListener("change", function ()
                    {
                        if (self.element.checked)
                        {
                            self.field.setAttribute("type", "text");
                        } else
                        {
                            self.field.setAttribute("type", "password");
                        }
                    }, false);
                }
            };
            
            document.addEventListener("DOMContentLoaded", function()
            {
                var checkbox = document.querySelector("#show-hide"),
                psw = document.querySelector("#psw"),
                form = document.querySelector("#login");
                
                form.addEventListener("submit", function(e)
                {
                    e.prevendDefault();
                }, false);
                
                var toggler = new PasswordToggler(checkbox, psw);
            });

})();





