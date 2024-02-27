//this was created for client side scripting
function CorrectIncorrectBorder(element, status)
{
    if(status == true)
    {
        element.style = "border: 2px solid red;";
    }
    else
    {
        element.style = "border: 2px solid green;";
    }
}

function validateForm()
{
    let isValid = true;

    var submitBtn = document.getElementById("submit-btn");
    var Name = document.forms["contact-form"]["name"];
    var EmailAddress = document.forms["contact-form"]["email"];
    var Title = document.forms["contact-form"]["title"];
    var Message = document.forms["contact-form"]["message"];

    let FormInputs = [Name,EmailAddress,Title,Message]
    FormInputs.forEach(currentValue => {
        if(currentValue.value == "")
        {
            isValid = false;
            CorrectIncorrectBorder(currentValue, true)
        }
        else
        {
            CorrectIncorrectBorder(currentValue, false)
        }
    });
    if(isValid == false)
    {
        alert("Note: All Inputs are Mandatory, please amend your entries and try again.")
    }
    return isValid;
}