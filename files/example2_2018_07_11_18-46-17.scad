taille_du_cube = 20; // [10:petit,20:Moyen,30:grand]

diametre_du_trou = 2.5; 


// Quel est la profondeur du trou central ?
profondeur_du_trou = 1; // [0,1,5,50]

montrer_roues = "oui"; // [oui,non]

// Quel est l'epaisseur d'une roue?
epaisseur_roues = 21; // [1:40]

// ignore this variable!
// foo=1;

// don't turn functions into params!
function BEZ03(u) = pow((1-u), 3);

// ignore variable values
bing = 3+4;
baz = 3 / hole_depth;
//ign
hole_radius = diametre_du_trou/2;


difference() {
  union() {
    translate([0, 0, taille_du_cube/2]) cube([taille_du_cube,taille_du_cube,taille_du_cube], center=true);
    if (montrer_roues == "oui") {
      translate([0, 0,  taille_du_cube/2]) rotate([0, 90, 0]) {
        cylinder(r= taille_du_cube/2, h=taille_du_cube+(epaisseur_roues*2), center=true);
      }
    }
  }
  translate([0, 0, taille_du_cube-profondeur_du_trou]) cylinder(r=hole_radius, h=profondeur_du_trou);
}
        
