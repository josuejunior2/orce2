<div class="modal modal-blur fade" id="modal-full-width" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Verificar localização</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="ratio ratio-21x9">
                <gmp-map center="-15.779444,-47.929444" zoom="5" map-id="DEMO_MAP_ID">
                    <gmp-advanced-marker position="" title="site" id="gmaps">
                        <img class="flag-icon"
                            src="https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png"/>
                    </gmp-advanced-marker>
                  </gmp-map>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
