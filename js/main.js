// Declare variables
let feed = document.getElementById('feed');
const maximum_constant = 4;
let max_material = maximum_constant;

// when scrolling more files are loaded
window.addEventListener("scroll", function() {
    let position = $(window).scrollTop();
    let bottom = $(document).height() - $(window).height();
    let btn = document.getElementById("searchbtn");

    if (!btn) {
        if(position == bottom) {
            getFiles();
        }
    }
})

// Search & Filter Bar
let searchbar = document.getElementsByClassName("search-bar");
let content = document.getElementsByClassName("content-block");
let input = document.getElementById("search");
if (input) {
    getFiles();
    input.addEventListener("input", myInput);
}

function myInput() {
    let btn = document.getElementById("searchbtn");
    let filter = document.getElementById("filter-bar");
    // if search input value not empty string then show filter bar
    if (input.value != "") {
        if (!btn) {
            let searchbtn = document.createElement("a");
            searchbtn.insertAdjacentHTML("beforeend",'<i class="fas fa-search"></i>');
            searchbtn.setAttribute("id", "searchbtn");
            searchbar[0].append(searchbtn);
            filter.insertAdjacentHTML("beforeend", `
                <div id="filter-field">
                <label for="subject">Choose Subject Area:</label>
                <select name="subject" id="subject">
                    <option value="">--</option>
                    <option value="accounting">Accounting & Finance</option>
                    <option value="architecture">Architecture</option>
                    <option value="arts">Arts & Humanities</option>
                    <option value="business">Business & Management Studies</option>
                    <option value="computerscience">Computer Science & Information Systems</option>
                    <option value="economics">Economics & Econometrics</option>
                    <option value="engineering">Engineering & Technology</option>
                    <option value="mechanical">Mechanical, Aeronautical & Manufacturing Engineering</option>
                    <option value="medicine">Medicine</option>
                    <option value="law">Law</option>
                </select>
                </div>
                `);
            filter.removeAttribute("style");
        }
    } else if (input.value == ""){
        max_material = maximum_constant;
        getFiles();
        btn.parentNode.removeChild(btn);
        let filter_field = document.getElementById("filter-field");
        filter_field.parentNode.removeChild(filter_field);
        filter.style.visibility = "hidden";
    }
    // Searchbutton link 
    if (document.getElementById("searchbtn")) {
        document.getElementById("searchbtn").setAttribute("onclick", "Search()");
    }
}

// AJAX - GET users
let users = [];
let ajax = new XMLHttpRequest();
ajax.open("GET", "./api/api.php", true);
ajax.send();
ajax.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        users = JSON.parse(this.responseText);
        let id = [];
        let idAjax = new XMLHttpRequest();
        idAjax.open("GET", "./api/userid.php", true);
        idAjax.send();
        idAjax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                id = JSON.parse(this.responseText);
                LoggedUser(users, id[0]['userid']);
            }
        }
    }
}

function LoggedUser (users,id) {
    let hello = document.getElementById('hello');
    hello.insertAdjacentText("beforeend","Hello, " + users[id]['fname'] + "!");
    let hellochatter = document.getElementById('hellochatter');
    if (hellochatter) {
        hellochatter.insertAdjacentText("beforeend","Welcome to the Chat, " + users[id]['fname'] + "!");
    }
    let name = document.getElementById('name');
    if (name) {
        name.insertAdjacentText("beforeend", users[id]['fname']+" "+users[id]['lname']);
        let email = document.getElementById('email');
        email.insertAdjacentText("beforeend", users[id]['email']);
        let university = document.getElementById('university');
        university.insertAdjacentText("beforeend", users[id]['university']);
        let semester = document.getElementById('semester');
        semester.insertAdjacentText("beforeend", users[id]['semester']);
        let course = document.getElementById('course');
        course.insertAdjacentText("beforeend", users[id]['studycourse']);
    }
}


function getFiles () {
    let filesGet = new XMLHttpRequest();
    filesGet.open("GET", "./api/files/files.php", true);
    filesGet.send();
    filesGet.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.responseText);
            setFeedList(data);
        }
    }
}

// Searchbar/feedshow functions
function Search () {
    clearFeed();
    let search = input.value;
    if (search && search.trim().length > 0) {
        search = search.trim().toLowerCase();
    }
    let course = document.getElementById('subject');
    let searchFiles = new XMLHttpRequest();
    searchFiles.open("GET", "./api/files/files.php", true);
    searchFiles.send();
    searchFiles.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let data = JSON.parse(this.responseText);
            let searchdata = json2array(data);
            // search/filter for file - name
            let namesearch = searchdata.filter(files => {
                return files.fname.toLowerCase().includes(search);
            });
            // search/filter for file - info (description)
            let infosearch = searchdata.filter(files => {
                if (files.info) {
                    return files.info.toLowerCase().includes(search);
                }
            });
            // setting intersection = shown searched material
            let intersection = namesearch; // default -> search file name
            if (namesearch.length == 0 || infosearch.length !=0) {
                // 2nd possible state -> search info
                intersection = infosearch;
            }
            // additional filter for the course (if set)
            if (course.value != '') {
                // filter based on course
                let coursesearch = searchdata.filter(files => {
                    return files.course.toLowerCase().includes(course.value);
                });
                if (namesearch.length == 0 || infosearch.length !=0) {
                    intersection = infosearch.filter(element => coursesearch.includes(element)); // infosearch filter course filter
                } else {
                    intersection = namesearch.filter(element => coursesearch.includes(element)); // namesearch filter course filter
                }
            }
            setFeedList(intersection);
        }
    }    
}

// Search Users
function SearchUsers () {
    let searchbar = document.getElementById("searchusers");
    let search = searchbar.value;
    if (search && search.trim().length > 0) {
        search = search.trim().toLowerCase();
    }
    if (search.trim().length != 0) {
        let ajax = new XMLHttpRequest();
        ajax.open("GET", "./api/api.php", true);
        ajax.send();
        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                data = JSON.parse(this.responseText);
                let users = json2array(data);
                // filter for university
                let unisearch = users.filter(user => {
                    return user.university.toLowerCase().includes(search);
                });
                let div = document.getElementById('userresults');
                // remove all recent searches
                while(div.firstChild) {
                    div.removeChild(div.firstChild);
                }
                div.insertAdjacentHTML("beforeend", '<h2 id="fheading">Search Results</h2>');
                // show all found search results
                for (user in unisearch) {
                    div.insertAdjacentHTML("beforeend", `
                <div class="material">
                    <div class="materialcontent">
                        <i id="nametag"></i><h5>Name: `+unisearch[user]['fname']+" "+unisearch[user]['lname']+`</h5>
                        <i id="nametag"></i><h5>Email: `+unisearch[user]['email']+`</h5>
                        <i id="universitytag"></i><h5>University: `+unisearch[user]['university']+`</h5>
                        <i id="coursetag"></i><h5>Course: `+unisearch[user]['studycourse']+`</h5>
                        <i id="infotag"></i><h5>Semester: `+unisearch[user]['semester']+`</h5>
                    </div>
                </div>
                `);
                }
                if (unisearch.length == 0) {
                    div.insertAdjacentHTML("beforeend", `
                <h4>No results found.</h4>
                `);
                }
            }
        }
    } else {
        // if no keyword specified
        let div = document.getElementById('userresults');
        div.insertAdjacentHTML("beforeend", `
                <h4>You need to specify a keyword!</h4>
                `);
    }
}

// all available courses
let courses = {
    accounting : ['Accounting & Finance'],
    architecture : ['Architecture'],
    arts : ['Arts & Humanities'],
    business: ['Business & Management Studies'],
    computerscience : ['Computer Science & Information Systems'],
    economics : ['Economics & Econometrics'],
    engineering : ['Engineering & Technology'],
    mechanical : ['Mechanical, Aeronautical & Manufacturing Engineering'],
    medicine : ['Medicine'],
    law : ['Law']
}

function setFeedList(data) {
    clearFeed();
    feed.insertAdjacentHTML("beforeend", '<h2 id="fheading">Your feed</h2>');
    for (index in data) {
        if (feed) {
            // allow maximum of 3 shown material
            if (feed.childElementCount < max_material) {
                let specifyCourse = data[index]['course'];
                if (courses[specifyCourse]) {
                    let description = data[index]['info'];
                    if (data[index]['info'] == null) {
                        description = '---';
                    }
                    let ftype = data[index]['ftype'];
                    let type = "";
                    if (ftype.includes("pdf")) {
                        type = "img/pdf.png";
                    }
                    if (ftype.includes("jpeg") || ftype.includes("png")) {
                        type = "img/image.png";
                    }
                    if (ftype.includes("word")) {
                        type = "img/docx.png";
                    }
                    feed.insertAdjacentHTML("beforeend", `
                    <div class="material">
                        <div class="materialcontent">
                            <i class="fas fa-file-signature" id="nametag"></i><h5>Name: `+data[index]['fname']+`</h5>
                            <i class="fas fa-university" id="universitytag"></i><h5>University: `+data[index]['university']+`</h5>
                            <i class="fas fa-layer-group" id="coursetag"></i><h5>Course: `+courses[specifyCourse]+`</h5>
                            <i class="fas fa-info-circle" id="infotag"></i><h5>Description: `+description+`</h5>
                        </div>
                        <div class="materialtype">
                            <img src="`+type+`" alt="ftype" class="typeimg">
                        </div>
                        <div id="previewbtn">
                            <a target="_blank" href="/studyfam/api/files/view.php?id=`+data[index]['id']+`">PREVIEW</a>
                        </div>
                    </div>
                    `);
                }
            }
        }
    } 
    // if no data
    if (data.length === 0) {
        feed.insertAdjacentHTML("beforeend", `
                <h4>No results found.</h4>
            `);
    }
    let btn = document.getElementById("searchbtn");
    if (btn) {
        let feedheading = document.getElementById('fheading');
        feedheading.innerText = 'Search Results';
    }
    // increase max material (used when scrolling)
    max_material = max_material+3;
}

function clearFeed() {
    while(feed.firstChild) {
        feed.removeChild(feed.firstChild);
    }
}

// DARK MODE
(function() {
    let onpageLoad = localStorage.getItem("theme") || "";
    let element = document.body;
    element.classList.add(onpageLoad);
    document.getElementById("theme").textContent =
        localStorage.getItem("theme") || "light";
})();

function themeToggle() {
    let element = document.body;
    element.classList.toggle("dark-mode");

    let theme = localStorage.getItem("theme");
    if (theme && theme === "dark-mode") {
        localStorage.setItem("theme", "");
    } else {
        localStorage.setItem("theme", "dark-mode");
    }
    document.getElementById("theme").textContent = localStorage.getItem("theme");
}


// HELP FUNCTIONS
function json2array(json){
    var result = [];
    var keys = Object.keys(json);
    keys.forEach(function(key){
        result.push(json[key]);
    });
    return result;
}