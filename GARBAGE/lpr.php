<form name="foo" method="post" enctype="multipart/form-data">
    <input type="file" name="" id="image-upload" value="/test.jpg">
</form>
<input type="button" value="test" id="clicker">

<script src="assets/libs/jquery/jquery.min.js"></script>
<script>
    const urlToObject= async(filepath)=> {
    const response = await fetch(filepath);
    // here image is url/location of image
    const blob = await response.blob();
    const file = new File([blob], 'image.jpg', {type: blob.type});
    const dataTransfer = new DataTransfer();
    const fileInputElement = document.getElementById('image-upload');
    dataTransfer.items.add(file);
    fileInputElement.files = dataTransfer.files;
    console.log(file);
    }

    urlToObject('assets/lprsnaps/10.10.2.12-1675515897.jpg').then(function(){
        console.log("hh");
    });

    $("#clicker").click(function(){


        const formData = new FormData();
        // append the name of the required services separated by comma, its value can be 'anpr' or 'mmr' or 'anpr,mmr'
        formData.append('service', 'anpr,mmr');
        // find the file input element where the user has selected an image to upload
        const fileInputElement = document.getElementById('image-upload');
        
        /*const myFile = new File([blob], 'test.jpg', {
            type: 'image/jpeg',
            lastModified: new Date(),
        });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(myFile);
        fileInputElement.files = dataTransfer.files;*/
        console.log(fileInputElement.files[0]);
        // an image file selected from your computer by clicking the browse button of a form
        formData.append('image', fileInputElement.files[0]);
        //formData.append('image', '@test.jpg;type=image/jpeg')
        // append the location of your device, not required, but can speed up the recognition process
        //formData.append('location', 'HUN');
        // append the maximum number of recognitions, not required (1 by default), but if you want us to return more vehicles per one image, than increase this number
        formData.append('maxreads', 1);
        // set up the request
        const options = {
        method: 'POST',
        body: formData,
        headers: {
            'X-Api-Key': 'kYQhj3VUCC9R3QIDtqjif9kFYMl0LCRG3A1MDVvd',
        },
        };

        fetch('https://api.cloud.adaptiverecognition.com/vehicle/afr', options)
        .then(function(response){ 
            
            console.log('Response received:', response)
            return response.json();
        }).then(function(results){
            console.log(results.data); 
        })
        .catch((err) => console.log('Error:', err));

});
</script>