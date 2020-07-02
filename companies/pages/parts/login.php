<div class="container">

    <div class="row">

        <div class="col-md-8 mx-auto">
            
            

            <form action="" class="login_form" method="post">
                
                <h2 class="text-center">Login</h2>

                <?php if(isset($_SESSION['danger'])): ?>

                <div class="alert alert-danger mt-3 mb-3">

                    <?php echo $_SESSION['danger']; ?>

                </div>

                <?php unset($_SESSION['danger']); endif; ?>

                <div class="form-group">

                    <label for="email">Email</label>

                    <input style="border: 1px solid #bbb;" class="form-control" type="email" placeholder="E-mail" name="email" id="email">

                </div>

                <div class="form-group">

                    <label for="password">Password</label>

                    <input style="border: 1px solid #bbb;" class="form-control" type="password" placeholder="Password" name="password" id="password">

                </div>

                <div class="form-group">

                    <input type="submit" class="btn btn-primary" value="Login" name="login" id="login">

                </div>



            </form>

        </div>

    </div>

</div>