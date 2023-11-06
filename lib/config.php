<?php

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function alerts(string $text) {
    echo '<script> alert("' . $text . '"); </script>';
}

function location(string $page, bool $header = true) {
    if ($header) {
        header("Location: " . $page);
        die();
    } else {
        echo "<script> window.location.href='" . $page . "'; </script>";
    }
}
/*

OBJECTIVES / CAPABILITIES

a. Allowing donors and charitable organizations to register their own account 
    through a registration module;
    - no email verification - (done)
    - include birthdate for legal age and gender - (done)
    - recently added should be on top of the list [admin side] - (done)
    - option alphabetized list of user [admin side] - (done)
    - visibility of user in every form - (50%) idk how to design it

b. Providing donors through the donor dashboard;
    - List of donations from recent to previous - (done)
    - who will verify the donation ? (to org dashboard) (done)
    - make it manageable like in date should be control or disable the previous dates. (done)
    - filter out search bar based on charities and sort (done)

c. Letting donors choose their preferred charities 
    to donate in-kind or monetary value through the charity module. 
    - own design of organization

    - no control on date
    - list of approved charity event
    - event not manageable
    - no verification of amount donated
    - need to upload gcash receipt
    - make item in kind donation tru drop down list
    - no control on date of delivery
    - system admin approved the donation? (to org dashboard)
    - edit donation details

d. Allowing charitable organizations to post their own timelines about 
    how they will use the allocated funds and to know their planned 
    events along with the background of the organization through an organization dashboard.
    - no allocation of funds

e. Allowing donors and the charitable organization 
    to communicate through direct messaging (DONE)
    - org cannot direct pm or the first one to pm the donor
    - no communication in other charitable org
    - no visible notif on home page
    - need to enter chat page to see notif
    - should be on tab menu not on a dropped down

f. Providing donors to rate and comment on their interactions 
    with the charity organizations through a rating module. (DONE)
    - other donor cannot donate on the existing event
    - control on over target not possible

g. Displaying the nearest charitable organizations through 2D mapping.
    - with nearest charity box include name (DONE)
    - user and charity can mark their own address in registration (DONE)

    - search charity (done)
    - activity nearest location

h. Monitoring the donors and registered charitable organizations 
    by managing through a system dashboard.
    - with history of accounts (DONE)
    - total donation per charity (DONE)
    - add and set active status of charities/donor
    - add reason input box in deny

    - editable by donor and by charity org all info
    - validation of email for one of validation of charity org (DONE)

DATABASE TABLES (9)

new dbtables based on my recommendation
dbcharitease

- tblusers 1
    - user_id (primary key)
    - email (unique) (true email)
    - password (hashed)
    - account_type (0, 1, 2 or donor, charity, admin)
    - is_verified (0, 1) - for email verification
    - verification_pin (random varchar for email click)
    - bday
    - gender
    - date_created
    - date_approved (what date the account is approved for admin)

- tblclients (tbldonors, tblorgs, tbladmins) 2
    - client_id (primary key to user_id)
    - client_name 
    - client_phone
    - client_contact_name
    - client_address
    - client_bio (can be null either description ng org/donor/admin)
    - client_lat (for address)
    - client_lng (for address)
    - client_user_type (0, 1 or individual, organization)
    - client_org_type (0, 1, 2, 3, 4, or types of organization)
    - date_founded (can be null)
    - is_approved (0, 1)

- tblchats 3
    - convo_id
    - sender_id (from client_id)
    - receiver_id (from client_id)
    - initiated_by (0, 1, 2 - charity, donor, admin)
    - message (4096)
    - is_read (is suitable?)
    - timestamp

- tblratings 4
    - rating_id
    - donor_id (from client_id)
    - org_id (from client_id)
    - rating (1 - 5)
    - review
    - timestamp

- tbldonations 5
    - donation_id
    - donor_id (from client_id)
    - org_id (from client_id)
    - event_id (from event_id)
    - donation_type (0, 1 or inkind, monetary)
    - donation_amount (quantity of item or money)
    - donation_name
    - donation_date (delivery date or date of sended the money)
    - donation_status (0, 1, 2 or pending, approved, rejected)

- tblevents (tblorgtimeline) 6
    - event_id
    - org_id (from client_id)
    - event_title
    - event_type (0, 1 or announcement, event)
    - event_description
    - event_start_date
    - event_end_date
    - event_status (should we add or check the date only?)
    - post_date
    - is_approved or status (status from the admin)

- tblsubevents (part of tblorgtimeline that panels discuss) 7
    - sub_event_id
    - event_id (from event_id)
    - org_id (from client_id)
    - sub_event_title
    - sub_event_description
    - post_date

- tblcollections 8
    - collection_id
    - event_id (from event_id)
    - current_inkind
    - target_inkind
    - current_funds
    - target_funds

- tblimages 9
    - image_id
    - table_id (foreign key to the relevant table)
    - permit_type (0, 1, 2, 3 or permit, valid ids, blog, event)
    - category (0, 1, 2, 3 or donor_permit, org_permit, event_image, donation_image)
    - image_name (name of image name and type)
    - image_data (blob)

- tblnotifs 10 (todo)
    - notif_id
    - table_id (foreign key to the relevant table)
    - notif_type (message, approved, rejected, update)
    - content (text on whats happening)
    - status (unread or read)
    - created_at (date when the notif started)

- tblapprovals 11 (todo)
    - approval_id
    - table_id (foreign key to the relevant table)
    - requester_id (unknown as of now)
    - request_status (pending, approved, rejected)
    - reason (reason for rejected)
    - request_date (time when the approval is created)
    - approval_date (time when the approval is checked)

Need Images (
    Queue Timeline (IMAGES), 
    Timeline (IMAGES), 
    Donations (INKIND, MONETARY - RECEIPT), 
    Organization (ICON, PERMIT), 
    Donors (VALID IDS, PERMIT, ICON)
)

OLD TABLES
User Account Table: 1 /
- user_id (primary key) (key for all needs)
- email (unique) (true email)
- password (encrypted)
- account_type (donor or charity or admin)
- is_verified (boolean, default to false) (para po ito sa verification sa email, (clickable ba?))
- verification_pin (varchar, unique, nullable example = 'J7HkP2e9Ls')
- bday
- gender

Donors Table: 2 /
- donor_id (primary key, foreign key to User Account Table)
- donor_name (name of donor)
- donor_contact_name (contact person name ng donor)
- donor_address (address ng donor)
- donor_type (individual or organization)
- org_type (if organization (refer to registration))
- donor_phone (contact number of donor)
- date_approved (kelan inapprove yung account nya?)
- is_approved (boolean) (check kung naapprove)
- donor_lat
- donor_lng

Charity Organizations Table: 3 /
- org_id (primary key, foreign key to User Account Table)
- org_name (organization name)
- org_person_name (organization who manage the org)
- org_phone (organization phone number)
- org_address (organization address)
- is_approved (boolean) (check kung na approved)
- org_description (can be null) (description ng organization)
- org_type (type of organization)
- date_founded (date kelan sila nagsimula)
- date_approved (date kung kelan napprove yung charity)
- org_lat = (can be null) organization latitude
- org_lng = (can be null) organization longitude

Conversations Table: 4 /
- convo_id (primary key)
- donor_id_to (foreign key to Donors Table) can be null?
- donor_id_from (foreign key to Donors Table) can be null?
- org_id_to (foreign key to Charity Organizations Table) can be null?
- org_id_from (foreign key to Charity Organizations Table) can be null?
- initiated_by (donor or charity or admin)
- message varchar(4096)
- timestamp
- is_read

Donor Ratings Table: 5
- rating_id (primary key)
- donor_id (foreign key to Donors Table)
- org_id (foreign key to Charity Organizations Table)
- rating
- review
- timestamp

Donations Table: 6
- donation_id (primary key)
- donor_id (foreign key to User Accounts Table)
- org_id (foreign key to Charity Organizations Table)
- event_id (foreign key to Timeline Table)
- donation_type (values of "inkind" or "monetary")
- donation_amount (decimal) (quantity of item or money)
- donation_name (for inkind)
- donation_description (for inkind text)
- donation_category
- donation_date (datetime) (delivery date or date of sended the money)
- status (values of "pending", "approved", or "rejected") for donors (charity organization admin)

Charity Organization Timeline Events Table: 7
- event_id (primary key)
- org_id (foreign key to Charity Organizations Table)
- event_title (title of event)
- event_type (announcement or event) 
- event_description text (text description)
- event_start_date (date started)
- event_end_date (date ended) (can be null)
- current_inkind (can be null)
- target_inkind (can be null)
- current_funds (can be null)
- target_funds (can be null)
- timestamp (timestamp kung kelan naedit)
- post_status (checking if approval sa admin (pending, approve or disapprove))
- status (status ng event (for example Ended, Started, Pending))

Image Table: 8
- image_id (primary key)
- table_id (foreign key to the relevant table)
- permit_type (varchar) = (permit, valid ids, blog, event)
- category (e.g., donor_permit, org_permit, event_image, edit_image, donation_image)
- image_name (varchar) <-- Column to store the image file name
- image_type (varchar) <-- New column to store the image type or format (eg., jpeg, png, jpg)
- image_data (BLOB)

Payment Table: 9 TODO MAKE IT SIMPLE
- payment_id (primary key)
- event_id (foreign key to Timeline CAN BE NULL)
- org_id (foreign key to Organization Table)
- method_type (GCash, Paypal, Maya)
- account_details (Details of Account by TEXT json_encpde) (picture nalang daw?)

Need Images (
    Queue Timeline (IMAGES), 
    Timeline (IMAGES), 
    Donations (INKIND, MONETARY - RECEIPT), 
    Organization (ICON, PERMIT), 
    Donors (VALID IDS, PERMIT, ICON)
)
*/

?>