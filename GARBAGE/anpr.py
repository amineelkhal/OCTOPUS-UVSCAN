
# Import necessary resources. 
# The requests module will handle the HTTP POST request, then provides the response.
# The json library will parse the response into a python dict object.
import requests
import json

# The POST request's multipart_data dictionary. 
# This dict contains every necessary information for the requests module
# to assemble the required POST request.
multipart_data = {
    # This contains the image pointer. It can be a binary data too
    'image': ('test.jpg', open('test.jpg', 'rb')),
    # The other parameters. Notice that the first element of the tuple is None, 
    # because multipart/form-data is originally provided for file transfer, 
    # but with this method we can send parameters too.
    'service': (None, 'anpr,mmr'),
    'location': (None, 'HUN'),
    'maxreads': (None, 1),
}

# The request were made here. 
# In the headers variable we provide the API key, the files variable will contain the
# recently made multipart_data dictionary.
resp = requests.post('https://api.cloud.adaptiverecognition.com/vehicle/eur',
                     files=multipart_data,
                     # NOTE: your API Key is confidential and needs to be treated
                     # like a password: never commit it to a version control
                     # repository and always store it in a secure way.
                     headers={'X-Api-Key' : 'kYQhj3VUCC9R3QIDtqjif9kFYMl0LCRG3A1MDVvd'})

# The json.loads method parses the REST API response text part 
# into a python dictionary object
resp_dict = json.loads(resp.text)

# Print the results
print(resp.text)

#print(f"The parsed dictionary's content: {resp_dict}")
