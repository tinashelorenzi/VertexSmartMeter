from pydantic import BaseModel
from typing import Optional

class KWUsage(BaseModel):
    id: Optional[int]
    device_id: str
    timestamp: str
    KWusage: float
    UnitsLeft: int 