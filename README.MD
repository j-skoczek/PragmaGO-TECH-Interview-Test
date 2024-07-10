# Couple of notes for the reviewer:


 - To avoid issues with operations on floats, numbers which could be float are multiplied by 100 and treated as centesimal values in integers.
 - I have changed the FeeCalculator interface to accept an array of FeeProposal value objects.
 - Both files in Model/ dir are basically value objects.
    -  I'm not sure what php version is run in the production app. We could use constructor property promotion and readonly properties here.
 - I've decided to keep the input data in txt files and I've used Repository/ to read these files. This can be extended to use a db connection.
    -  I have ignored other currency codes. For the sake of simplicity I assume we're only handling PLN here.
    -  Some really basic checks are there to check the input data. I assume that the input data is correct.
    -  Input files for the calculation are stored in Resources/
 - To keep the code easily testable FeeCalculator service depends on Math service.
    -  Functions in the Math service are pretty generic and could be used in other services.
    -  Math is treated as a math function aggregator, so no interface for it. Should it need to grow a decision would have to be made if a new service will be created or if the old one will be extended.
 - index.php is used for debugging. Simple setup where all needed objects are created and injected to the controller.
 - tests are written using php unit and can be run using `composer test`
 - code is checked using php_codesniffer
