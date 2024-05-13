<div class="footer mt-1 bg-dark text-center p-3">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p>&copy; <span id="currentYear"></span> Utoolity+. All rights reserved.</p>

            </div>
        </div>
    </div>
</div>


<script>
    // Automatically update the copyright year
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

<style>
    
 .footer {
    background-color: #f8f9fa; 
    padding: 20px 0;
    position: relative; 
    width: 100%;

}

.footer p {
    margin: 0;
    color: white;
};


</style>