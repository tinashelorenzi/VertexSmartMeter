from pydantic import BaseModel
from typing import Optional

class SmartMeter(BaseModel):
    id: Optional[int]
    device_id: str
    last_update_id: Optional[int] = 0 