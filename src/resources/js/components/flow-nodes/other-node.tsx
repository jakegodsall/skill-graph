import React from 'react';
import { Handle, Position } from 'reactflow';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Zap, ExternalLink } from 'lucide-react';

interface OtherNodeProps {
  data: {
    id: number;
    name: string;
    description?: string;
    status: 'not_started' | 'in_progress' | 'completed' | 'paused';
    url?: string;
    skill: {
      name: string;
      color: string;
    };
    statusColor: string;
  };
}

export default function OtherNode({ data }: OtherNodeProps) {
  const handleClick = () => {
    if (data.url) {
      window.open(data.url, '_blank');
    }
  };

  return (
    <Card className="min-w-48 max-w-64 shadow-md hover:shadow-lg transition-shadow cursor-pointer">
      <Handle type="target" position={Position.Top} />
      
      <CardHeader className="pb-2">
        <div className="flex items-center justify-between">
          <div className="flex items-center space-x-2">
            <Zap className="w-4 h-4 text-indigo-600" />
            <div
              className="w-3 h-3 rounded-full"
              style={{ backgroundColor: data.skill.color }}
            />
          </div>
          {data.url && (
            <ExternalLink 
              className="w-4 h-4 text-gray-400 hover:text-gray-600" 
              onClick={handleClick}
            />
          )}
        </div>
        <CardTitle className="text-sm font-medium">{data.name}</CardTitle>
      </CardHeader>
      
      <CardContent className="pt-0">
        <div className="space-y-2">
          <Badge className={`text-xs ${data.statusColor}`}>
            {data.status.replace('_', ' ')}
          </Badge>
          <p className="text-xs text-gray-600">{data.skill.name}</p>
          {data.description && (
            <p className="text-xs text-gray-500 line-clamp-2">
              {data.description}
            </p>
          )}
        </div>
      </CardContent>
      
      <Handle type="source" position={Position.Bottom} />
    </Card>
  );
} 