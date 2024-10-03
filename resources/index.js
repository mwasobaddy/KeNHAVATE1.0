

document.addEventListener("DOMContentLoaded", function() {
    const sign_up = Array.from(document.querySelectorAll('.sign_up'));
    const close_signup = document.querySelector('.close_signup');
    const overlay_signup = document.querySelector('.overlay_signup');

    const log_in = Array.from(document.querySelectorAll('.log_in'));
    const close_login = document.querySelector('.close_login');
    const overlay_login = document.querySelector('.overlay_login');

    const close_OTP = document.querySelector('.close_OTP');
    const overlay_OTP = document.querySelector('.overlay_OTP');


    const signin_btn = document.querySelector('.signin_btn');
    const signup_btn = document.querySelector('.signup_btn');
    const verify_btn = document.querySelector('.verify_btn');


    verify_btn.addEventListener('click', ()=>{

        fetch('indexx.php?reset_signup_form_processed=true');
    });

    sign_up.forEach((sign_up) => {
        sign_up.addEventListener('click', () => {
            overlay_signup.classList.add('active');
            overlay_login.classList.remove('active');
            overlay_OTP.classList.remove('active');
    
            fetch('indexx.php?set_dont_have_an_account_true=true');
        });
    });

    close_signup.addEventListener('click', ()=>{
        overlay_signup.classList.remove('active');
        overlay_login.classList.remove('active');
        overlay_OTP.classList.remove('active');

        fetch('indexx.php?reset_dont_have_an_account=true');
    });

    log_in.forEach((log_in) => {
        log_in.addEventListener('click', () => {
            overlay_login.classList.add('active');
            overlay_signup.classList.remove('active');
            overlay_OTP.classList.remove('active');
    
            fetch('indexx.php?set_signup_form_processed_true=true');
        });
    });

    close_login.addEventListener('click', ()=>{
        overlay_login.classList.remove('active');
        overlay_signup.classList.remove('active');
        overlay_OTP.classList.remove('active');

        fetch('indexx.php?reset_signup_form_processed=true');
    });

    signin_btn.addEventListener('click', ()=>{
        overlay_login.classList.add('active');
        overlay_signup.classList.remove('active');
        overlay_OTP.classList.remove('active');

        fetch('indexx.php?set_signup_form_processed_true=true');
    });

    signup_btn.addEventListener('click', ()=>{
        overlay_login.classList.remove('active');
        overlay_signup.classList.add('active');
        overlay_OTP.classList.remove('active');
    });

    close_OTP.addEventListener('click', ()=>{
        overlay_login.classList.remove('active');
        overlay_signup.classList.remove('active');
        overlay_OTP.classList.remove('active');
        
        fetch('indexx.php?reset_first_form_processed=true');
    });

    //trigger btns
    const about_btn = Array.from(document.querySelectorAll('.about_btn'));
    const climate_btn = Array.from(document.querySelectorAll('.climate_btn'));
    const contact_btn = Array.from(document.querySelectorAll('.contact_btn'));
    const customer_btn = Array.from(document.querySelectorAll('.customer_btn'));
    const terms_btn = Array.from(document.querySelectorAll('.terms_btn'));
    const areas_btn = Array.from(document.querySelectorAll('.areas_btn'));
    const challenge_btn = Array.from(document.querySelectorAll('.challenge_btn'));
    const help_btn = Array.from(document.querySelectorAll('.help_btn'));
    const quality_btn = Array.from(document.querySelectorAll('.quality_btn'));
    const revenue_btn = Array.from(document.querySelectorAll('.revenue_btn'));
    const road_btn = Array.from(document.querySelectorAll('.road_btn'));
    const tech_btn = Array.from(document.querySelectorAll('.tech_btn'));
    const value_btn = Array.from(document.querySelectorAll('.value_btn'));

    //overlays
    const overlay_about_us = document.querySelector('.overlay_about_us');
    const overlay_climate = document.querySelector('.overlay_climate');
    const overlay_contact_us = document.querySelector('.overlay_contact_us');
    const overlay_customer = document.querySelector('.overlay_customer');
    const overlay_terms_and_conditions = document.querySelector('.overlay_terms_and_conditions');
    const overlay_innovation_areas = document.querySelector('.overlay_innovation_areas');
    const overlay_innovation_challenge = document.querySelector('.overlay_innovation_challenge');
    const overlay_help = document.querySelector('.overlay_help');
    const overlay_quality = document.querySelector('.overlay_quality');
    const overlay_revenue = document.querySelector('.overlay_revenue');
    const overlay_road = document.querySelector('.overlay_road');
    const overlay_tech = document.querySelector('.overlay_tech');
    const overlay_value = document.querySelector('.overlay_value');

    //close btns
    const close_about = document.querySelector('.close_about');
    const close_climate = document.querySelector('.close_climate');
    const close_contact = document.querySelector('.close_contact');
    const close_customer = document.querySelector('.close_customer');
    const close_terms = document.querySelector('.close_terms');
    const close_areas = document.querySelector('.close_areas');
    const close_challenge = document.querySelector('.close_challenge');
    const close_help = document.querySelector('.close_help');
    const close_quality = document.querySelector('.close_quality');
    const close_revenue = document.querySelector('.close_revenue');
    const close_road = document.querySelector('.close_road');
    const close_tech = document.querySelector('.close_tech');
    const close_value = document.querySelector('.close_value');

    about_btn.forEach((about_btn) => {
        about_btn.addEventListener('click', () => {
            overlay_about_us.classList.add('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_about.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    climate_btn.forEach((climate_btn) => {
        climate_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.add('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_climate.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    contact_btn.forEach((contact_btn) => {
        contact_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.add('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_contact.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    customer_btn.forEach((customer_btn) => {
        customer_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.add('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_customer.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    terms_btn.forEach((terms_btn) => {
        terms_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.add('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_terms.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    challenge_btn.forEach((challenge_btn) => {
        challenge_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.add('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_challenge.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    help_btn.forEach((help_btn) => {
        help_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.add('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_help.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    quality_btn.forEach((quality_btn) => {
        quality_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.add('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_quality.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    road_btn.forEach((road_btn) => {
        road_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.add('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_road.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    tech_btn.forEach((tech_btn) => {
        tech_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.add('active');
            overlay_value.classList.remove('active');
        });
    });

    close_tech.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    value_btn.forEach((value_btn) => {
        value_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.remove('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.add('active');
        });
    });

    close_value.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    revenue_btn.forEach((revenue_btn) => {
        revenue_btn.addEventListener('click', () => {
            overlay_about_us.classList.remove('active');
            overlay_climate.classList.remove('active');
            overlay_contact_us.classList.remove('active');
            overlay_customer.classList.remove('active');
            overlay_terms_and_conditions.classList.remove('active');
            overlay_innovation_areas.classList.remove('active');
            overlay_innovation_challenge.classList.remove('active');
            overlay_help.classList.remove('active');
            overlay_quality.classList.remove('active');
            overlay_revenue.classList.add('active');
            overlay_road.classList.remove('active');
            overlay_tech.classList.remove('active');
            overlay_value.classList.remove('active');
        });
    });

    close_revenue.addEventListener('click', ()=>{
        overlay_about_us.classList.remove('active');
        overlay_climate.classList.remove('active');
        overlay_contact_us.classList.remove('active');
        overlay_customer.classList.remove('active');
        overlay_terms_and_conditions.classList.remove('active');
        overlay_innovation_areas.classList.remove('active');
        overlay_innovation_challenge.classList.remove('active');
        overlay_help.classList.remove('active');
        overlay_quality.classList.remove('active');
        overlay_revenue.classList.remove('active');
        overlay_road.classList.remove('active');
        overlay_tech.classList.remove('active');
        overlay_value.classList.remove('active');
    });

    const activate_menu = document.querySelector('.activate_menu');
    const deactivate_menu = document.querySelector('.deactivate_menu');
    const top = document.querySelector('.top');

    activate_menu.addEventListener('click', ()=>{
        top.classList.add('active');
        activate_menu.classList.remove('active');
        deactivate_menu.classList.add('active');
    });

    deactivate_menu.addEventListener('click', ()=>{
        top.classList.remove('active');
        activate_menu.classList.add('active');
        deactivate_menu.classList.remove('active');
    });
    
});