<div class="interfaceUser">
    <div class="titleView">
        <h1> <u>USER INSERTION</u></h1>
    </div>
    <div class="formView">
        <form id="formUser" enctype="multipart/form-data">
            <div class="formInput">
                <div class="formElement">
                    <div class="form-group">
                        <label for="username" class="form-label">NAME</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="form-label">FIRSTNAME</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="form-label">GENDER</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="MALE">MALE</option>
                            <option value="FEMALE">FEMALE</option>
                        </select>
                        <!-- <input type="text" class="form-control" required> -->
                    </div>
                    <div class="form-group">
                        <label for="userEmail" class="form-label">EMAIL</label>
                        <input type="email" name="userEmail" id="userEmail" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="pwd" class="form-label">PASSWORD</label>
                        <input type="password" name="pwd" id="pwd" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">FUNCTION</label>
                        <input type="text" name="role" id="role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cin" class="form-label">CIN</label>
                        <input type="number" min=0 oninput="validity.valid || (value='')" class="form-control" name="cin" id="cin" required>
                    </div>
                </div>
                <div class="photo">
                    <div class="form-group">
                        <h1>ADD PHOTO</h1>
                        <label for="photo" class="form-label" style="color:white;font-size:64px;margin-left:70px"> <i class="fa-solid fa-file"></i></label>
                        <div>
                            <input type="file" class="form-control" name="photo" id="photo" multiple style="visibility: hidden;">
                        </div>
                    </div>
                    <div class="photoContent">
                    </div>
                </div>
            </div>
            <div class="buttonRegister">
                <button type="submit">REGISTER</button>
            </div>
        </form>
    </div>
</div>
</div>