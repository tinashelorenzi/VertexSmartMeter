from fastapi import FastAPI, Request, HTTPException, status, Depends
from fastapi.responses import JSONResponse
from pydantic import BaseModel
from typing import Optional
from authorized_tokens import AUTHORIZED_TOKENS
import database_driver

app = FastAPI()

class RechargeRequest(BaseModel):
    device_id: str
    units: int

def get_token(request: Request):
    auth = request.headers.get("Authorization")
    if not auth or not auth.startswith("Bearer "):
        raise HTTPException(status_code=status.HTTP_401_UNAUTHORIZED, detail="Missing or invalid token")
    token = auth.split(" ", 1)[1]
    if token not in AUTHORIZED_TOKENS:
        raise HTTPException(status_code=status.HTTP_403_FORBIDDEN, detail="Unauthorized token")
    return token

@app.get("/")
def read_root():
    return {"message": "API is running"}

@app.get("/health")
def health_check():
    return JSONResponse(content={"status": "ok", "message": "Server is healthy"})

@app.post("/recharge")
def recharge(request: RechargeRequest, token: str = Depends(get_token)):
    device_id = request.device_id
    units = request.units
    if not database_driver.validate_device(device_id):
        raise HTTPException(status_code=404, detail="Device not found")
    last_update_id = database_driver.get_last_update_id(device_id)
    new_update_id = last_update_id + 1
    success = database_driver.create_update(device_id, units, new_update_id)
    if not success:
        raise HTTPException(status_code=500, detail="Failed to create update entry")
    return {"message": "Recharge request successful", "device_id": device_id, "update_id": new_update_id, "units": units}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)